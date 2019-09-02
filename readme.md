# laravel-batuta

`laravel-batuta` is a package for Laravel which provides a permissions solutions which is compatible
with the `Authorization` system provided by Laravel.

## Some concepts
`laravel-batuta` uses some concepts which must be understood before using the package.

Let's use an example of an e-commerce project. Your website is selling **items**. Then `item` is a clear
example of a **Resource**. Over that **Resource** we will be able to make **Actions**. We can create as actions 
as we want to for each **Resource**. In this case, these actions are enough for the example:
* read
* write
* delete
* change_price 

Notice as we are extracted `change_price` as an independent action from `write`. This allow us to define users who can
change an item but not its price, users who will be able to change only the price and users who can change both. What
**actions** you create for each **Resource** depends on which permissions you want to assign to the users, is up to you!

Then, *Users* will have permissions over each **action** of that resource. That permission can be `true` 
(can perform that action), `false` (can not perform that action) or `null` 
(the permissions is not defined for that **user** and that **action**. `laravel-batuta` will consider that case as 
it was `false`.)

In order to create groups of permissions to make things easy, you can assign a *User* to a one (or many) *Role(s)*. 
**Users** will inherit the permissions which are unassigned (returns `null` value) from the **Roles** associated.
In case any of the roles has this permission for a given **action** set, then `false` is returned.

Summing up, a **User** belongs to a one (or many) **Roles**. Both, **Users** and **Roles** has **Permissions** over 
**Actions** performed to a **Resource**. When a permission does not exists for a User and its Roles, then `false`
is returned.  

## Installation

First, add `laravel-batuta` dependency in your composer configuration:
```
composer require kodilab/laravel-batuta
```

Optionally, you can publish the configuration file in case you want to modify the parameters (for example, the table
names which `laravel-batuta` is going to create in the next step).  You can do it using this command:

```
php artisan vendor:publish --provider="Kodilab\LaravelBatuta\LaravelBatutaProvider" --tag="config"
```

This will generate the configuration file in your config directory (`config/batuta.php`). This configuration file
has an explanation of each parameter so, take it a look if you want to change something.

Once configuration fits you, its time to publish the migrations. 

```
php artisan vendor:publish --provider="Kodilab\LaravelBatuta\LaravelBatutaProvider" --tag="migrations"
```

This will generate the migrations files in your migration directory (`database/migrations`).
Only modify these migration files if you know what are you doing.

Then, apply migrations:

```
php artisan migrate
```

Next, changes in the `User` model must be done. You should add the `UserPermissions` trait and the `HasPermissions`
interface to provide the `User` with the relationships and methods required.

```
class User extends Model implements HasPermissions
{
    use UserPermissions;
}
``` 

`laravel-batuta` has been installed and configured to be used!

## Creating our Resources

**Resource** represents an element which requires permissions to perform actions over it. Can represents elements of your
business model, "places" of your web or whatever you need permissions for.

**Resources** are persisted on your database thus you can create them using `migration` files, `seeds` or any solution you
use for feeding your database.

In this case, we are going to use a `migration` file to create a resource.

```
class CreateResources extends Migration
{
    public function up()
    {
        LaravelBatuta::createResource('item');
    }

    public function down()
    {
        LaravelBatuta::removeResource('item');
    }
}
```

`larave-batuta` provides method for creating resources and for remove them.
The name of a resource must be **unique**. A resource name must not contain blank spaces,
accents, or uppercase letters. 

So, if you call this:
```
LaravelBatuta::createResource('Item cool');
```

Your resource will be `item-cool`,

## Creating actions for a resource

An **action** is an operation which your users can perform over your resource:


