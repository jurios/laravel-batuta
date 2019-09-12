# laravel-batuta
[![Build Status](https://travis-ci.com/jurios/laravel-batuta.svg?branch=master)](https://travis-ci.com/jurios/laravel-batuta)

`laravel-batuta` is a package for Laravel which provides a permissions solutions which is compatible
with the `Authorization` system provided by Laravel.

## Some concepts
`laravel-batuta` uses some concepts which must be understood before using the package.

Let's use an example of an e-commerce project. Your website is selling **items**. Then `item` is a clear
example of a **resource**. Over that **resource** we will be able to make **actions**. We can create as actions 
as we want for each resource. In this case, we could list the following actions (composed of `verb` + `resource`):

* read item
* write item
* delete item
* update-price item 

Notice as we are extracting `update-price` as an independent action from `write`. This allow us to define users who can
update an item but not its price, users who will be able to change only the price and users who can perform both actions. 
What and how many actions you create for each resource depends on your needs!

Then, *users* will have permissions over each **action**. That permission can be `true` (can perform that action), 
`false` (can not perform that action). In case a permission over an action for a user is not define, then it is
considered as `false`.

In order to create groups of permissions to make things easy, you can assign a **role** to *users*. 
Then, **users** will inherit a permission from the roles assigned when the given permission is not defined for the user. 

Summing up, a **user** belongs to a one (or many) **roles**. Both, **users** and **roles** have **permissions** over 
**actions**. When a permission does not exists for a **user**, then check if any of the **user** roles has the given permission.

## Installation

First, add `laravel-batuta` dependency in your composer configuration:
```
composer require kodilab/laravel-batuta
```

Once the dependency is added to your project, you must publish the required migrations. Those migrations will create the
tables required by `laravel-batuta`. The basic [initial roles](#initial-roles) will be created, also. With the following command, 
the migrations files will be generated:

```
php artisan batuta:install
```

Optionally, you can generate a configuration file in case you want to modify the parameters (for example, the table
names which `laravel-batuta` is going to create or the initial role names).  You can do it using this command:

```
php artisan batuta:config
```

This will generate the configuration file in your config directory (`config/batuta.php`). This configuration file
contains an explanation of each parameter. So, take it a look if you want to change something before start.

Once configuration fits you, its time to fire the migrations. 

```
php artisan migrate
```

Congratulations, `laravel-batuta` has been installed and configured to be used!

## BatutaBuilder
For some of the following section it's suggested use of `BatutaBuilder`. This class provide some
helper methods for managing `actions` and `roles`. Is important to know that all those methods uses directly 
`QueryBuilder` under the hood in order to avoid instance `Eloquent Models`. 
This allow us to use `BatutaBuilder` in `migration` files without any problem.

Please, take this into consideration as they won't fire `Eloquent ORM` events. If you want to use
`Eloquent ORM` features, manage `actions` and `roles` using the `create()` and `delete()` methods provided
by `Eloquen`. 

## Actions

As explained in the [concept section](#some-concepts), actions represents what a user can do over a resource. In the
previous example, a item's actions could be, for example, `create`, `read`, `write`. 
We could create more specific actions in order to restrict the authorization like: 
`update-price`, `delete`, `update-images`.

That actions are always associated with a `resource`. Thus, in this case the name of those `actions` would be 
`create item`, `read item`, `write item`, `update-price item`, `update-images item` and so on...

### Creating and removing actions
All those actions must be created and persisted in the `action` table in order to be used. 
You can create actions using the `BatutaBuilder` helper methods. In the following example, we are using a migration
file, but you can use any method you prefer:

```(php)
use Kodilab\LaravelBatuta\Builder\BatutaBuilder;

class CreateBatutaPermissionsTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        BatutaBuilder::createAction('update-price', 'item', 'Can change the item price')
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        BatutaBuilder::removeAction('update-price', 'item');
    }
}
```

#### createAction(string $verb, string $resource, string $description = false)
Creates a new action. `verb` and `resource` will be `slugged` before creation. So, for example `verb='update price'` 
and resource `Item` generates the following `action`:

* `verb` : 'update-price'
* `resource` : 'item'
* `name` : 'update-price item'

#### removeAction(string $verb, string $resource)
Remove an existing action based on the action `verb` and the `resource`.

## Roles
A Role is a group of granted permissions. You can assign multiple roles to a user and multiple users to a role 
(many to many relationship). By default, when a user belongs to a role, any permission not defined for the user will 
inherits the permissions from the roles. So, for example, if `update-price item` is not defined in the user permission list, then will
look if any of the roles has `update-price item` granted. That behaviour can be changed for any case of user and role 
(go to [disabling permission inheritance](#disabling-permission-inheritance)). 

### Initial roles
By default, `laravel-batuta` creates two `initial roles` when you run the `migrations`. You can customize the names of
its role by changing the name on the configuration file **before** you run the migrations.
If you want to change it afterwards, then you can do it updating the model or changing it on the database.

Those roles are important because both have some "special" behaviours:

* **Default role**: Default role will be the role assigned by default to users when a user is created
or doesn't have one (at least, one role must be assigned to a user). This only happens when you [enable roles]() for you
user model. This role can **not** be removed.
 
* **God role**: The god role is a special role. Each user who belongs to this role will be granted to do
any action. No matter if you change the permission for that user or for that role. **It will always have full granted 
permissions**. This role can **not** be removed.

 
### Creating and removing roles
As you did for creating `actions`, you can use `BatutaBuilder` for managing `Roles` as well.
```(php)
use Kodilab\LaravelBatuta\Builder\BatutaBuilder;

class CreateBatutaPermissionsTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        BatutaBuilder::createRole('editor')
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        BatutaBuilder::removeRole('editor');
    }
}
```

#### createRole(string $name)
Creates a new role.

#### removeAction(string $verb, string $resource)
Remove an existing role based on the name.

### Assign and detach roles to users
In order to assign roles to a user and detach them, you must add the `HasRoles` trait to the `User` model:
```
class User extends Model
{
    use HasRoles;
}
``` 

Once you add this trait, you can use the following methods:


#### addRole(Role $role)
Add a new role, if it is not already added, to the user. Once a role is added to the user, it will inherits the role
permissions.

* **Role $role**: The `Role` instance to be assigned.

```
use Kodilab\LaravelBatuta\Models\Role;

...

$role = Role::find(1);

$user->addRole($role);
```

#### public function bulkRoles(array $roleIds, bool $detaching = false)
Adds a group of roles.

* **array $roleIds**: Array which contains `Role` ids.
* **bool $detaching = false**: If `detaching = true` then the previous roles assigned to the user will be removed.

```
$roles = [1, 2];

$user->bulkRoles($roles, true);
```

#### public function removeRole(Role $role)
Removes a role from the users assignments.

* **Role $role**: The `Role` instance to be assigned.

```
$role = Role::find(1);

$user->addRole($role);
$user->removeRole($role);
```
 
#### public function belongsToRole(Role $role)
Returns whether a role is assigned to a user

* **Role $role**: The `Role` instance to be assigned.

**Returns:**
* `true`: The user belongs to this role
* `false`: The user doesn't belongs to this role
```
$user->belongsToRole($other);
```

#### public function isGod()
Returns whether the user belongs to the `god role`:
**Returns:**
* `true`: The user belongs to the `god role`
* `false`: The user doesn't belongs to the `god role`
```
$user->isGod();
```
 
## Granting permissions
Despite of the fact `Role` is ready to be used, changes in the `User` model must be done in order to provide 
permissions for the users. You should add the `UserPermissions` trait and implements the `Permissionable` 
interface also.
```
class User extends Model implements Permissionable
{
    use UserPermissions;
}
```
This will provide to the `User` new methods to deal with permissions. 

The methods listed here can be used for `Roles` and `Users`.

### Granting permissions to user or role
#### updatePermission(Action $action, bool $grant):void
Grant permission to a user/role for a given action.

* **Kodilab\LaravelBatuta\Models\Action|string $action**: The action instance or the action name 
* **bool $grant** : `true|false` 

```
    use Kodilab\LaravelBatuta\Models\Action;

    ...

    $action = Action::findByName('update-price item');

    if (!is_null($action)) {
        $user->updatePermission($action, true);
    }
```

You can use the action `name` instead of the action instance:
```
    $user->updatePermission('update-price item', true);
```

 
#### bulkPermissions(array $permissions, bool $detaching = false): void
Grant multiple permissions to a user/role.
 
* **array $permissions**: Associative array where the `key` is the action `id` and the `value` is `true|false`.
* **bool $detaching = false**: If `detaching = true` then the previous permissions assigned to the user will be removed.
 
```
   $permissions = [
       1 => true,
       2 => false
   ];

    $user->bulkPermissions($permissions, true);
```

### Retrieving a permission to user or role

#### hasPermission(Action|string $action): bool
Returns whether it has a permission. In case the permission is not defined, `false` is returned. 
If the `hasRoles` trait is being used, when the permission is not defined, then it will check the permission for the
belonging roles.

* **Kodilab\LaravelBatuta\Models\Action|string $action**: The action instance or the action name

**Returns:**
* `true`: The user is allowed to perform that action
* `false`: User is **not** allowed to perform that action

```
    $action = Action::get('update-price item');

    if($user->hasPermission($action)) {
        $item->changePrice();
    }
```

### Disabling permission inheritance
Sometimes you would like to disable permission inheritance (user inherits permission from its roles). You can do it 
adding the method `private function shouldInheritPermissions()`. That method should return `true` or `false` in case
you want or not inherit permission. So, for example:

```
class User extends... {
    ...

    private function shouldInheritPermissions()
    {
        if ($this->id === 1) {
            return false;
        }

        return true;
    }
}
```

The user which `id=1` won't inherit permissions no matter the roles it belongs to. The other users will inherit permission
as expected.

**Important:** users who belongs to `god role` won't be affected by the `shouldInheritPermissions()` method.
