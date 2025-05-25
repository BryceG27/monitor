# Technical Test - How to fire it up

## Setting-up development environment

The first step is to create a copy of the dotfile `.env.example` and rename it as `.env`. A simple `cp .env.example .env` should do the job.

### With Docker (suggested)

#### Things to know before starting with Docker
The `.env` file is setted to map ports for a multi-environment system: change `*_PORT_EXPOSED` to your port mapping preferences

#### Commands list

<ul>
    <li>
        Using docker, the only thing to do is insert the command <code>make</code> in the shell;      
    </li>
    <li>
        This command will: 
        <ol>
            <li>
                generate a key for your Laravel application;
            </li>
            <li>
                start all the containers needed;
            </li>
            <li>
                enter in the shell of the container named <strong><em>app</em></strong>;
            </li>
        </ol>
    </li>
    <li>
        In the shell of the app container you just need to insert the command <code>php artisan migrate</code> to setup your database
    </li>
</ul>

#### Makefile error
In case the Makefile and the make command won't work <em>(or you don't want to use it)</em> you you'll have to do the steps one by one by yourself:
<ol>
    <li>
        Install all the Laravel dependences with the command <code>docker compose run --rm composer install</code>;
    </li>
    <li>
        Start up all the containers with the command <code>docker compose up -d</code>;
    </li>
    <li>
        Enter in the shell of the app container (<code>docker exec -it app sh</code> will work just fine);
    </li>
    <li>
        Generate the key for Laravel using the command <code>php artisan key:generate</code>
    </li>
    <li>
        Only then you can setup the database with the command <code>php artisan migrate</code>;
    </li>
</ol>

### With the traditional way

#### Things to know before starting w/o Docker
 - The database parameters are setted to use Docker containers and variables. You'll need to change the variable <code>DB_HOST</code> to <em>localhost</em> or to <em>127.0.0.1</em> instead of <em>DB</em>. Of course you'll have to change <code>DB_DATABASE</code>, <code>DB_USERNAME</code> and <code>DB_PASSWORD</code> variables too.
 - For this version of Laravel (v.11), sqlite is the default choice: you need just to comment all <code>DB_*</code> variables except the CONNECTION one that needs to be <code>sqlite</code>.
 - This version of Laravel (v.11) needs at least the version 8.2 of PHP.

#### Commands list
<ol>
    <li>
        The first thing to do is install all the Laravel dependences with the command <code>compose install</code>;
    </li>
    <li>
        Use the command <code>php artisan key:generate</code> to generate the unique key for your app;
    </li>
    <li>
        Now you just need to insert the command <code>php artisan migrate</code> to setup your database.
    </li>
</ol>

## What now
At the moment your app should be ready to use and you can verify it looking for the home page (<a href="localhost:8000" _target="blank">localhost:8000</a>) where you will find the version of the installed Laravel.<br>
From now on you can start using all the APIs written in the <strong style="font-size: 16px">The APIs</strong> paragraph, but you could make just another step that could make your testing just a little easier seeding the database:

In the shell of your Docker container or your local directory you can:
<ul>
    <li>
        Create a test user using the command <code>php artisan db:seed</code>;
    </li>
    <strong>OR</strong>
    <li>Create a test user and some test products using the command <code>php artisan db:seed DevSeeder</code></li>
</ul>

Both commands will create a new user with <em>test@example.com</em> as email and <em>supersecurepassword</em> as password. <br>
The <em>DevSeeder</em> option will create some products to help you start create some orders.

## Testing

#### The testing environment is setted to be use out of the box with Docker. In case you are not using Docker, follow this steps:

- In the `.env.example` file you can change <code>DB_CONNECTION</code> parameter to sqlite and comment all the rest of <code>DB_*</code> parameters to have a simple to use database.
- Watch out! Every edit to the `.env*` files needs to be done with the app container shutted down or restarted!

#### OR

<ol>
    <li>
        Change the parameter <code>DB_PORT</code> from db_test to 127.0.0.1;
    </li>
    <li>
        Create a new database, <code>laravel_test_db</code> will do the job;
    </li>
    <li>
        Change the parameters <code>DB_USERNAME</code> and <code>DB_PASSWORD</code> as requested.
    </li>
</ol>

### The test framework
The test framework used for this project is PEST. It's been created by one of the Laravel Developers so the integration is flawless and the syntax of it is really simple.

The following tests are all written in the directory <code>tests/Feature</code>:
<ul>
    <li>
        <strong>\Auth\AuthenticationTest</strong> will:
        <ol>
            <li>
                Check if users can authenticate;
            </li>
            <li>
                Check if users cannot authenticate with wrong password;
            </li>
            <li>
                Check if users can logout;
            </li>
            <li>
                Check if users can operate if they are not logged.
            </li>
        </ol>
    </li>
    <li>
        <strong>\Auth\RegistrationTest</strong> will:
        <ol>
            <li>
                Check if users can register new users;
            </li>
            <li>
                Check if users can create a new user with API.
            </li>
        </ol>
    </li>
    <li>
        <strong>\Auth\UpdateTest</strong> will update user data with API.
    </li>
    <li>
        <strong>CRUDProductTest</strong> will:
        <ol>
            <li>
                Check if users can create a product;
            </li>
            <li>
                Check if users can update a product;
            </li>
            <li>
                Check if users can delete a product.
            </li>
        </ol>
    </li>
    <li>
        <strong>CRUDOrderTest</strong> will:
        <ol>
            <li>
                Check if users can create a order;
            </li>
            <li>
                Check if users can update a order;
            </li>
            <li>
                Check if users can delete a order.
            </li>
        </ol>
    </li>
</ul>

To start all the tests you have to use the command `php artisan test` from the shell of your Docker container or of your local repository.
