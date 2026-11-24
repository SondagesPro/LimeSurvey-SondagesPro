# Sophisticated online survey software
![Build status](https://github.com/SondagesPro/LimeSurvey-SondagesPro/actions/workflows/main.yml/badge.svg)

## About
Limesurvey is the number one open-source survey software.

Advanced features like branching and multiple question types make it a valuable partner for survey-creation.

**This is an unoffical fork of LimeSurvey, use at your own risk**

**Regarding security**: we try to fix all known security vulnerabilities. Given the version of twig, **there are no plans to fix minor security vulnerabilities that require access to the template editor**. As a reminder, accessing the template editor allows you to add any JavaScript functionality, including scripts with cross-site scripting attacks.

## How to install

5.X branch are maintain on github, [see official LimeSurvey manual](https://manual.limesurvey.org/Installation_-_LimeSurvey_CE/en).

### Recommended security setting

We recommend setting `allowedHosts` on each instance. It protects against Host header injection, for example in password reset links. Add it to the `config` array of `application/config/config.php`:

```php
'config' => array(
    // ...
    'allowedHosts' => array('survey.example.org', 'www.survey.example.org'),
),
```

List every host name used to reach the instance, without scheme or port. Requests to any other host name get an HTTP 400 error. This setting is optional: without it, the host name is not checked.

### Disabled PHP functions

LimeSurvey works with these functions disabled in `php.ini`:

```ini
disable_functions = ini_set,ini_alter,ini_restore,set_time_limit,exec,shell_exec,system,passthru,proc_open,popen,pcntl_exec,putenv
```

With this setting:
- **Emails must be sent with SMTP.** With the PHP `mail()` method, PHPMailer always calls `ini_set` (for the bounce address), and the Sendmail and Qmail methods use `popen`. With PHP 8, every email then fails with a fatal error. Choose SMTP in Global settings > Email settings.
- The `memory_limit` and `max_execution_time` values of `config.php` are not applied: set them in `php.ini`.
- Do not set `timeout`, `cookieMode`, `gCProbability` or `useTransparentSessionID` in the `session` component of `config.php`: Yii uses `ini_set` for them.

### Twig themes and Yii models (after 5.6.94)

**Important change:** the Twig sandbox now applies to Yii models (survey participant, response, question…). A theme or a plugin view can only read the properties listed in the `sandboxConfig` of `application/config/internal.php`. Any other property or method gets an error, for example:

```
Calling "getattributes" method on a "Token_123456" object is not allowed.
```

The participant model (`Token`) has no allowed property: `oToken.tid`, `oToken.token` or `oToken.attributes` all fail. Iterating over a model (`for`, `merge`, `first`, `last`, `keys`) and `json_encode` on a model are refused too.

To fix a theme, use the arrays sent to the view instead of the model, for example `aTokens` instead of `oTokens` for TokenUsersListAndManage.

If all the users who can edit survey themes are trusted, you can allow more properties in `application/config/config.php`. The class name covers its child classes (`Token` covers `Token_123456`):

```php
'components' => array(
    // ...
    'twigRenderer' => array(
        'sandboxConfig' => array(
            'properties' => array(
                'Token' => array('tid', 'token', 'email', 'firstname', 'lastname', 'attributes'),
            ),
            'methods' => array(
                'Token' => array('getAttribute'),
            ),
        ),
    ),
),
```

Only add what your themes use. `attributes` and `getAttribute` give access to every column of the participant, including email and attributes. If `twigRenderer` is already in your `config.php`, add `sandboxConfig` into it: there must be only one `twigRenderer` key.

To get back the previous behaviour, where every column of a model can be read, set the base class of the compiled templates:

```php
'twigRenderer' => array(
    'options' => array(
        'base_template_class' => 'Twig_Template',
    ),
),
```

This removes the protection for all the models: every user who can edit a theme can read any column of any model sent to a view. Properties like `attributes` still need to be in `properties`. The protection on iteration and `json_encode` stays active.

## Requirements

### Minimal
The absolute minimal requirements are:
 - Apache >= 2.4 | nginx >= 1.1 | any other php-ready webserver
 - 8.0 >= php >= 7.2.5 
    - with mbstring and pdo-database drivers
 - mysql >= 5.5.9 | pgsql >= 9 | mariadb >= 5.5  | mssql >= 2005

### Recommended
We recommend the following setup
 - Web server: nginx (most recent stable version)
 - PHP >= 7.4
    - with php-fpm, mbstring, gd2 with freetype, imap, ldap, zip, zlib and database drivers
 - MariaDB or MySQL (most recent stable version)

## Manual
for more information please refer to our [homepage](http://www.limesurvey.org), or have a look at the [manual](http://manual.limesurvey.org) 

## License
LimeSurvey software is licensed under the [GPL 2.0](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html), or at your choice any later version of the license.

Pictures and the LimeSurvey Logo are registered trademarks of LimeSurvey GmbH, Hamburg, Germany.
