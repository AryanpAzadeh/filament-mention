# Filament Mention Plugin
The **Mention** plugin allows you to easily mention users in your Filament application using the Filament RichText editor. It supports extracting specific fields from the mentioned user, such as their username, and id. The plugin offers both **static search** (preloaded data) and **dynamic search** (real-time database queries) for mentions.

---

## Features
- **Mention users** in the Filament RichText editor.
- **Extract specific fields** from the mentioned user (e.g., username, id).
- **Static search**: Preload and search from a dataset.
- **Dynamic search**: Fetch data from the database in real-time.
- **Customizable user model and fields**: Use your own `User` model and define which fields to display.
- **Customizable mention trigger character**: Change the default `@` trigger to any character.
- **Customizable suggestion limits**: Control the number of suggestions displayed and the minimum text length to trigger the search.
- **Avatar and URL support**: Display user avatars and link to their profiles.

---

## Requirements
- PHP 7.4 or higher
- Laravel 8.0 or higher
- Filament 3.2 or higher


## Installation

1. Install the package via Composer:
   ```bash
   composer require asmit/mention
    ```
2. Publish the package assets:
   ```bash
   php artisan filament:assets
   ```
3. Publish the configuration file:
   ```bash
   php artisan vendor:publish --provider="Asmit\Mention\MentionServiceProvider" --tag="asmit-mention-config"
   ```
This will create a `mention.php` file in your `config` directory. You can customize the configuration according to your needs.

---
## Configuration
The configuration file (config/mention.php) allows you to customize the plugin behavior. Here’s an example configuration:
```php
return [
    'mentionable' => [
        'model' => \App\Models\User::class, // The model to use for mentions
        'column' => [
            'id' => 'id', // Unique identifier for the user
            'display_name' => 'name', // Display name for the mention
            'username' => 'username', // Username for the mention
            'avatar' => 'profile', // Avatar field (e.g., profile picture URL)
            'url' => 'admin/users/{id}', // URL to the user's profile
        ],
        'lookup_key' => 'username', // Used for static search (key in the dataset)
        'search_key' => 'username', // Used for dynamic search (database column)
    ],
    'default' => [
        'trigger_with' => '@', // Character to trigger mentions (e.g., @)
        'menu_show_min_length' => 2, // Minimum characters to type before showing suggestions
        'menu_item_limit' => 10, // Maximum number of suggestions to display
    ],
];
```
---

### Key Configuration Options:
 - ``mentionable.model``: The Eloquent model to use for mentions (e.g., User).
 - ``mentionable.column``: Map the fields to use for mentions (e.g., id, name, etc.).
 - ``mentionable.lookup_key``: Used for static search (key in the dataset).
 - ``mentionable.search_key``: Used for dynamic search (database column).
 - ``default.trigger_with``: Character to trigger mentions (e.g., @).
 - ``default.menu_show_min_length``: Minimum characters to type before showing suggestions.
 - ``default.menu_item_limit``: Maximum number of suggestions to display.

---
## Usage
### 1. Static Search
Static search preloads all mentionable data and searches within that dataset. For static search you can you ``RichMentionEditor`` field.

The ``RichMentionEditor`` fetch all the mentionable data first and then search the mention item from the fetched data.

```php
use Asmit\Mention\Forms\Components\FetchMentionEditor;

RichMentionEditor::make('bio')
    ->columnSpanFull(),
```
You can also change the data by pass the closure function ``mentionsItems`` in the ``RichMentionEditor`` field.

example:
```php
RichMentionEditor::make('bio')
  ->mentionsItems(function () {
      return User::all()->map(function ($user) {
          return [
              'username' => $user->username,
              'name' => $user->name,
              'avatar' => $user->profile,
              'url' => 'admin/users/' . $user->id,
          ];
      })->toArray();
  })
```

#### Key Points
 - The ``mentionsItems`` method should return an array of mentionable items.
 - Map the data to include ``id``, ``username``, ``name``, ``avatar``, and ``url``.
 - Use the ``lookup_key`` to search the mentionable item.

You can change the lookup_key with chaining the method ``lookupKey`` in the ``RichMentionEditor`` field.
```php
RichMentionEditor::make('bio')
  ->mentionsItems(function () {
      return User::all()->map(function ($user) {
          return [
                'id' => $user->id,
                'username' => $user->username,
                'name' => $user->name,
                'avatar' => $user->profile,
                'url' => 'admin/users/' . $user->id,
          ];
      })->toArray();
  })
    ->lookupKey('username')
```
> NOTE: The data should be mapped like the above example.

### 2. Dynamic Search
Dynamic search fetches mentionable data from the database in real-time. Use the FetchMentionEditor field for this purpose. 

For dynamic search you can you ``FetchMentionEditor`` field.

> NOTE: The search_key must be the column name of your table.

Before use the ``FetchMentionEditor`` field you need to implement the ``Mentionable`` interface in your livewire page. And then ```use Asmit\Mention\Traits\Mentionable;``` in your livewire page.
It will add the method ``getMentionableItems(string $searhKey)`` in your livewire page. You can use this method to fetch the mentionable data.

```php
use Asmit\Mention\Forms\Components\FetchMentionEditor;

FetchMentionEditor::make('Fetch')
    ->columnSpanFull(),
```
> You can override the method ``getMentionableItems`` in your livewire page to fetch the mentionable data.

