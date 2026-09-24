<?php

namespace ls\tests;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

class IpAddressAnonymizeTest extends TestBaseClassWeb
{
    /**
     * Import survey in tests/surveys/.
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        /* Login */
        $username = getenv('ADMINUSERNAME');
        if (!$username) {
            $username = 'admin';
        }

        $password = getenv('PASSWORD');
        if (!$password) {
            $password = 'password';
        }
        // Browser login.
        self::adminLogin($username, $password);

    }

    /**
     * Test IP anonymization.
     */
    public function testIpAnonymizeInActiveSurvey()
    {
        $surveyFile = self::$surveysFolder . '/limesurvey_survey_573837.lss';
        self::importSurvey($surveyFile);

        $urlMan = \Yii::app()->urlManager;
        $urlMan->setBaseUrl('http://' . self::$domain . '/index.php');
        $url = $urlMan->createUrl(
            'surveyAdministration/view/surveyid/'.self::$surveyId
        );
        self::$webDriver->get($url);
        sleep(1);

        self::$webDriver->dismissModal();

        // Click "Activate survey".
        $overview = self::$webDriver->findElement(WebDriverBy::id('ls-activate-survey'));
        $overview->click();

        sleep(1);

        // Confirm.
        $overview = self::$webDriver->findElement(WebDriverBy::id('activateSurvey__basicSettings--proceed'));
        $overview->click();

        sleep(1);

        // Click "No, thanks"
        $overview = self::$webDriver->findElement(WebDriverBy::id('activateTokenTable__selector--no'));
        $overview->click();

        sleep(1);

        // Click "Run survey".
        $exceuteBtn = self::$webDriver->findById('execute_survey_button') ;
        $exceuteBtn->click();

        sleep(1);

        // Switch to new tab.
        $windowHandles = self::$webDriver->getWindowHandles();
        self::$webDriver->switchTo()->window(
            end($windowHandles)
        );

        sleep(1);

        // New tab with active survey.
        $nextButton = self::$webDriver->findElement(WebDriverBy::id('ls-button-submit'));
        $nextButton->click();
        sleep(2);

        //now check if ip was anonymized (ipv4, last digit should be 0)
        //get ipadr from table survey_573837 ...
        $models = \Response::model(self::$surveyId)->findAll();
        $response = $models[0];
        $this->assertIsAnonymizedIp($response->ipaddr ?? null);
    }

    /**
     * Test so that previous behaviour is still working.
     */
    public function testNormalActiveSurvey()
    {
        $surveyFile = self::$surveysFolder . '/limesurvey_survey_573837.lss';
        self::importSurvey($surveyFile);

        $urlMan = \Yii::app()->urlManager;
        $urlMan->setBaseUrl('http://' . self::$domain . '/index.php');
        $url = $urlMan->createUrl(
            'surveyAdministration/view/surveyid/'.self::$surveyId
        );
        self::$webDriver->get($url);
        sleep(1);

        self::$webDriver->dismissModal();

        //set ipanonymize to off ...
        $survey = \Survey::model()->findByPk(self::$surveyId);
        $survey->ipanonymize = 'N';
        $survey->save();

        // Click "Activate survey".
        $overview = self::$webDriver->findElement(WebDriverBy::id('ls-activate-survey'));
        $overview->click();

        sleep(3);

        // Confirm.
        $overview = self::$webDriver->findElement(WebDriverBy::id('activateSurvey__basicSettings--proceed'));
        $overview->click();

        sleep(3);

        // Click "No, thanks"
        $overview = self::$webDriver->findElement(WebDriverBy::id('activateTokenTable__selector--no'));
        $overview->click();

        sleep(1);

        // Click "Run survey".
        $exceuteBtn = self::$webDriver->findById('execute_survey_button') ;
        $exceuteBtn->click();

        sleep(1);

        // Switch to new tab.
        $windowHandles = self::$webDriver->getWindowHandles();
        self::$webDriver->switchTo()->window(
            end($windowHandles)
        );

        sleep(2);

        // New tab with active survey.
        $nextButton = self::$webDriver->findElement(WebDriverBy::id('ls-button-submit'));
        $nextButton->click();

        sleep(2);

        //now check if ip was anonymized (ipv4, last digit should be 0)
        //get ipadr from table survey_573837 ...
        $models = \Response::model(self::$surveyId)->findAll();
        $response = $models[0];
        $this->assertContains(
            $response->ipaddr ?? null,
            ['127.0.0.1', '::1'],
            'Expected raw (non-anonymized) loopback ipaddr, got: ' . var_export($response->ipaddr ?? null, true)
        );
    }

    /**
     * Asserts that $ip looks like an anonymized loopback address: the last IPv4 octet,
     * or the last 5 IPv6 groups, zeroed out. The CI environment may route the browser's
     * loopback request over IPv4 (127.0.0.1) or IPv6 (::1) depending on the runner, so
     * the expected anonymized shape has to be derived from the address family actually
     * used rather than hardcoded.
     *
     * @param string|null $ip
     * @return void
     */
    private function assertIsAnonymizedIp($ip)
    {
        $this->assertNotNull($ip, 'Response has no ipaddr stored.');
        if (strpos($ip, ':') !== false) {
            $groups = explode(':', $ip);
            $this->assertCount(8, $groups, "Anonymized IPv6 address should have 8 groups: $ip");
            $this->assertSame(
                ['0', '0', '0', '0', '0'],
                array_slice($groups, -5),
                "Last 5 IPv6 groups should be anonymized to 0: $ip"
            );
        } else {
            $octets = explode('.', $ip);
            $this->assertCount(4, $octets, "Anonymized IPv4 address should have 4 octets: $ip");
            $this->assertSame('0', end($octets), "Last IPv4 octet should be anonymized to 0: $ip");
        }
    }
}
