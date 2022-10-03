<?php
namespace AnkitJain\RegistrationModule\Tests;

use PHPUnit_Framework_TestCase;
use AnkitJain\RegistrationModule\Login;
use AnkitJain\RegistrationModule\Register;
use AnkitJain\RegistrationModule\Validate;
require_once dirname(__DIR__) . '/config/database.php';


class TestUser
    extends
        PHPUnit_Framework_TestCase
{
    protected $obRegister;
    protected $obLogin;
    protected $obUser;
    protected $obValidate;


    public function setUp()
    {
        $this->obRegister = new Register();
        $this->obLogin = new Login();
        $this->obValidate = new Validate();
    }


    public function test_authRegister()
    {

        $output = $this->obRegister->authRegister(
            [
                "name" => 'Test',
                "email" => 'test@testing.com',
                "username" => 'test',
                "mob" => '1234567890',
                "passRegister" => 'testing'
            ]
        );
        $output = (array)json_decode($output);
        $this->assertEquals([
            'location' => 'http://localhost/registrationModule/account.php'
            ], $output);

    }

    /**
    * @depends test_authRegister
    *  Testing for the register with empty username
    */
    public function test_authregisterEmptyUsername()
    {
        $output = $this->obRegister->authregister(
            [
                "name" => 'Test',
                "email" => 'test@google.com',
                "username" => '',
                "mob" => '1234567890',
                "passRegister" => 'testing'
            ]
        );
        $output = (array)json_decode($output, True);
        $expectedOutput = [
            [
                "key" => "username",
                "value" => " *Enter the username"
            ]
        ];

        $this->assertEquals($expectedOutput, $output);
    }

    /**
    * @depends test_authRegister
    *  Testing for the register with invalid email credentials
    */
    public function test_authregisterInvalidEmail()
    {
        $output = $this->obRegister->authregister(
            [
                "name" => 'Test',
                "email" => 'test@-testing.com',
                "username" => 'abc',
                "mob" => '1234567890',
                "passRegister" => 'testing'
            ]
        );
        $output = (array)json_decode($output, True);
        $expectedOutput = [
            [
                "key" => "email",
                "value" => " *Enter correct Email address"
            ]
        ];

        $this->assertEquals($expectedOutput, $output);
    }

    /**
    * @depends test_authRegister
    *  Testing for the register with repeated credentials
    */
    public function test_authregisterInvalidCredentials()
    {
        $output = $this->obRegister->authregister(
            [
                "name" => 'Test',
                "email" => 'test@testing.com',
                "username" => 'test',
                "mob" => '1234567ese',
                "passRegister" => 'testing'
            ]
        );
        $output = (array)json_decode($output, True);
        $expectedOutput = [
            [
                "key" => "email",
                "value" => " *Email is already registered"
            ],
            [
                "key" => "username",
                "value" => " *Username is already registered"
            ],
            [
                "key" => "mob",
                "value" => " *Enter correct Mobile Number"
            ]
        ];

        $this->assertEquals($expectedOutput, $output);
    }

    /**
    * @depends test_authRegister
    *  Testing for the login with correct credentials
    */

    public function test_authLogin()
    {
        $expectedOutput = ['location' => 'http://localhost/registrationModule/account.php'];
        $outputEmail = $this->obLogin->authLogin(
            [
                "login" => 'test@testing.com',
                "passLogin" => 'testing'
            ]
        );
        $outputEmail = (array)json_decode($outputEmail);
        $outputUsername = $this->obLogin->authLogin(
            [
                "login" => 'test',
                "passLogin" => 'testing'
            ]
        );
        $outputUsername = (array)json_decode($outputUsername);
        $this->assertEquals($expectedOutput, $outputEmail);
        $this->assertEquals($expectedOutput, $outputUsername);
    }

    /**
    * @depends test_authRegister
    *  Testing for the login with empty credentials
    */

    public function test_authLoginEmptyValues()
    {
        $output = $this->obLogin->authLogin(
            [
                "login" => '',
                "passLogin" => ''
            ]
        );
        $output = (array)json_decode($output, True);
        $expectedOutput = [
            [
                "key" => "login",
                "value" => " *Enter the login field"
            ],
            [
                "key" => "passLogin",
                "value" => " *Enter the password"
            ]
        ];

        $this->assertEquals($expectedOutput, $output);
    }

    /**
    * @depends test_authRegister
    *  Testing for the login with invalid or wrong email
    */

    public function test_authLoginWrongEmail()
    {
        $output = $this->obLogin->authLogin(
            [
                "login" => 'email@-domain.com',
                "passLogin" => 'egfb'
            ]
        );
        $output = (array)json_decode($output, True);
        $expectedOutput = [
            [
                "key" => "login",
                "value" => " *Enter correct Email address"
            ]
        ];

        $this->assertEquals($expectedOutput, $output);
    }

    /**
    * @depends test_authRegister
    *  Testing for the login with invalid email credentials
    */
    public function test_authLoginInvalidUsernameEmail()
    {
        $output = $this->obLogin->authLogin(
            [
                "login" => 'invalid',
                "passLogin" => 'invalid'
            ]
        );
        $output = (array)json_decode($output, True);
        $expectedOutput = [
            [
                "key" => "login",
                "value" => " *Invalid username or email"
            ]
        ];

        $this->assertEquals($expectedOutput, $output);
    }

    /**
    * @depends test_authRegister
    *  Testing for the login with invalid password credentials
    */
    public function test_authLoginInvalidPassword()
    {
        $output = $this->obLogin->authLogin(
            [
                "login" => 'test',
                "passLogin" => 'invalid'
            ]
        );
        $output = (array)json_decode($output, True);
        $expectedOutput = [
            [
                "key" => "passLogin",
                "value" => " *Invalid password"
            ]
        ];
        $this->assertEquals($expectedOutput, $output);
    }


    /**
    * @depends test_authRegister
    *  Testing for the Validate::class for email
    */
    public function test_validateEmailInDb()
    {
        $output = $this->obValidate->validateEmailInDb('test@testing.com');
        $this->assertEquals(1, $output);
    }

    /**
    * @depends test_authRegister
    *  Testing for the Validate::class for username
    */
    public function test_validateUsernameInDb()
    {
        $output = $this->obValidate->validateUsernameInDb('test');
        $this->assertEquals(1, $output);
    }

    /**
    * @depends test_authRegister
    *  Testing for the Validate::class for non-existing username
    */
    public function test_validateUsernameInDbNot()
    {
        $output = $this->obValidate->validateUsernameInDb('abc');
        $this->assertEquals(0, $output);
    }

    /**
    * @depends test_authRegister
    *  Testing for the Validate::class for non-existing email
    */
    public function test_validateEmailInDbNot()
    {
        $output = $this->obValidate->validateEmailInDb('ankitjain28ma77@gmail.com');
        $this->assertEquals(0, $output);
    }

    /**
    *   @depends test_validateEmailInDbNot
    *  Empty the DB
    */
    public function test_EmptyDB()
    {
        $connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "TRUNCATE `login`";
        $this->assertTrue($connect->query($query));
        $query = "TRUNCATE `register`";
        $this->assertTrue($connect->query($query));
    }
}

