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
                start all the containers needed;
            </li>
            <li>
                import Node;
            </li>
            <li>
                install all of the Node dependencies;
            </li>
            <li>
                make a new build of the assets;
            </li>
            <li>
                enter in the shell of the container named <strong><em>app</em></strong>;
            </li>
        </ol>
    </li>
    <li>
        In the shell of the app container you need to:
        <ol>
            <li>
                generate a new key for laravel with the command: <code>php artisan key:generate</code>
            </li>
            <li>
                insert the command <code>php artisan migrate</code> to setup your database
            </li>
        </ol>
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
 - For this version of Laravel (v.12), sqlite is the default choice: you need just to comment all <code>DB_*</code> variables except the CONNECTION one that needs to be <code>sqlite</code>.
 - This version of Laravel (v.12) needs at least the version 8.2 of PHP.
 - You need Node and NPM to build the assets 

#### Commands list
<ol>
    <li>
        The first thing to do is install all the Laravel dependences with the command <code>compose install</code>;
    </li>
    <li>
        Use the command <code>php artisan key:generate</code> to generate the unique key for your app;
    </li>
    <li>
        Now you just need to insert the command <code>php artisan migrate</code> to setup your database;
    </li>
    <li>
        Than you can install all the Node packages;
    </li>
    <li>
        And build your assets with the command <code>npm run build</code>
    </li>
</ol>

## What now
At the moment your app should be ready to use and you can verify it looking for the home page (<a href="localhost:8000" _target="blank">localhost:8000</a>) where you will find the version of the installed Laravel.<br>
From now on you can start using all the APIs written in the <strong style="font-size: 16px">The APIs</strong> paragraph, but you could make just another step that could make your testing just a little easier seeding the database:

In the shell of your Docker container or your local directory you can:
<ul>
    <li>
        Create 50 products using the command <code>php artisan db:seed ProductsSeeder</code>;
    </li>
    <li>
        Create 50 orders using the command <code>php artisan db:seed OrdersSeeder</code>;
    </li>
    <strong>OR</strong>
    <li>Create a both using the command <code>php artisan db:seed DevSeeder</code></li>
</ul>

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

### Dipping down

<ul>
    <li>
        From the Homepage you will see 3 menus, <em>Home</em>, <em>Products</em>, <em>Orders</em>.
    </li>
</ul>

#### Products
<ul>
    <li>
        Entering the page, after the mouting of the component Index, will be recovered via an api call all the products that are not deleted which will be shown in the main table;
    </li>
    <li>
        In order to create a new product you have to open the dialog form trough the <code>Create Product</code> button;
        <ul>
            <li>
                Here you can set a name and a price for your new product.
            </li>
        </ul>
    </li>
    <li>
        To edit a product you have to click the green pencil in the first column of the table.
        <ul>
            <li>
                This action will open up the Form Dialog with all of the informations about the product.
            </li>
        </ul>
    </li>
    <li>
        On the right of the <code>Edit button</code>, you will find a trash can to soft eliminate the product after the confirmation of the action.
        <ul>
            <li>
                Watch out! If you try to eliminate a product that is already synced to a order, the app will inform you that the action is not allowed.
            </li>
        </ul>
    </li>
</ul>

#### Orders
<ul>
    <li>
        Entering the page, after the mounting of the component Index, will be recovered via two api calls all the orders and all the products that are not deleted. The Orders will be shown in the table component;
    </li>
    <li>
        In order to create a new product you have to open the dialog form trough the <code>Create Order</code> button;
        <ul>
            <li>
                Here you can set:
                <ul>
                    <li>A name for your order;</li>
                    <li>A description;</li>
                    <li>A date;</li>
                    <li>And, clicking on the product's rows in the table and selecting a quantity for them, you can attach some items for the new order</li>
                </ul>
            </li> 
        </ul>
    </li>
    <li>
        The first column of the Orders Table is an expandable row that includes all the order items related to the order and delete some column
    </li>
    <li>
        To edit a order you have to click the green pencil in the first column of the table.
        <ul>
            <li>
                This action will open up the Form Dialog with all of the informations about the order.
            </li>
            <li>
                By de-selecting the green rows and saving, you can also remove the items from the order
            </li>
        </ul>
    </li>
    <li>
        On the right of the <code>Edit button</code>, you will find a trash can to soft eliminate the order after the confirmation of the action.
    </li>
</ul>

### The test framework
The test framework used for this project is PEST. It's been created by one of the Laravel Developers so the integration is flawless and the syntax of it is really simple.

The following tests are all written in the directory <code>tests/Feature</code>:
<ul>
    <li>
        <strong>CRUDProductTest</strong> will:
        <ol>
            <li>
                Check if a product can be created;
            </li>
            <li>
                Check if a product can be updated;
            </li>
            <li>
                Check if a product can be deleted.
            </li>
        </ol>
    </li>
    <li>
        <strong>CRUDOrderTest</strong> will:
        <ol>
            <li>
                Check if a order can be created;
            </li>
            <li>
                Check if a order can be updated;
            </li>
            <li>
                Check if a order can be deleted.
            </li>
        </ol>
    </li>
</ul>

To start all the tests you have to use the command `php artisan test` from the shell of your Docker container or of your local repository.