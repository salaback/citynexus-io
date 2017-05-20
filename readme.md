## About CityNexus



### Methods

##### User Registration

Users are registered across the entire application in `public.users`.
Each record requires a first name, last name, and email address. The
password field will authorize access to any tenant the user as permission for

Their membership is stored in the memberships array. Aside from
super users, users will only be granted access to a tenant instance
if they have a membership array with the full domain of that
client used as the key.

Within a membership array will be a list of specific permissions
which will override any group level permissions. These permissions
are grouped as arrays of methods, each with either a true or false
value.  True grants positive permission, while false denys access
no matter what other permissions may exist.

The membership array will also include their title and department
within the organization.  If none exists, a null value should still
be saved.

If the user has been migrated from a legacy version of CityNexus
they may have a hashed password stored in their membership array
as well. If the user doesn't have a password, this password will be
tried and if it matches, the user will be prompted to update their
password which will then be made into their primary account 
password.

###### Groups and Permissions

Calling the user method ``$user->getGroupPermissions()`` returns an array of 
permissions that have been created based on merging the permissions of the
different groups a user is a member of. By default, a user has access to 
nothing within the system, but gains access by being added to groups. The user
will gain access to content, when given permission to it in any of their groups, 
however, if a permission is set to false in any group, they will be denied access.
For this reason, negative permissions should be used sparingly, and perhaps be
restricted to only one or two negative permissions at a time.

Permissions for groups are defined in an array at the top of the view ```auth.user_group.create```.

**Methods**

```App\User```
* ```allowed($set, $permision)```: When passed the ```$set``` (i.e. properties) and the ```$permission```
the method will return a boolean for the user based on all memberships.
* ```disallowed($set, $permission)```: Reverse of ```allowed()```

DEV REQUEST: Add in user specific permissions which will over ride any group
permissions.

######Example Membership Array

``` javascript
{
    "demo.citynexus.io": {
        "properties": {
            "view": "true",
            "show": "true",
            "merge": "false",
            "edit": "false",
            "create": "false"
        },
        "password": "################",
        "title": "Director of IT",
        "department": "Information Technology"
        }
}
```

### Background Jobs

#### Nightly

- `citynexus:client-info` - Updates key statistics about clients including 
user and dataset counts.

- `citynexus:searchindex {client_id}` - Reindexes all address, comments, file 
captions, and datasets. Should be run nightly.