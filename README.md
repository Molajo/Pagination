
=======
Molajo Pagination
=======

[![Build Status](https://travis-ci.org/Molajo/Pagination.png?branch=master)](https://travis-ci.org/Molajo/Pagination)

Provides easy pagination data for any PHP application.

## At a glance ...

1. Get the `page_url`, `query parameters` and `start` value from your preferred `Http Request Class.`
1. Run a query (or produce a list of items) using normal `offset` and `row limit` criteria.
2. Instantiate the `Molajo\Pagination` class, injecting it with the data and various pagination values.
3. Use the pagination data object to render the pagination interface.

The following is from a working example of a Pagination View located in  in the
[.dev/Sample/ Folder](https://github.com/Molajo/Pagination/tree/master/.dev/Example). To see the demo
on your local website, create an Apache Host using
[the Public Folder](https://github.com/Molajo/Pagination/tree/master/.dev/Sample/Public) as the Disk Location.
Then, use the Server Name as the address in your browser.

```php

/** 1. Routing: simulates the Router */
include __DIR__ . '/Route.php';

/** 2. Configuration Data */
include __DIR__ . '/RuntimeData.php';

/** 3. Get Mocked Data: could be any list of data */
include __DIR__ . '/MockData.php';

$data_instance = new \Molajo\Pagination\MockData(
    (int)$runtime_data->route->parameter_start,
    (int)$runtime_data->parameters->display_items_per_page_count
);

$mockdata = $data_instance->getData();

/** */
/** 4. Get Pagination Data (the main point!) */
/** */
$pagination_instance = new \Molajo\Pagination();

$row = $pagination_instance->getPaginationData(

// Configuration: variables your application must provide
    $runtime_data->parameters->display_items_per_page_count, // How many items are displayed on each page?
    $runtime_data->parameters->display_page_link_count, // 3 in this example => << < 1 2 3 > >>
    $runtime_data->parameters->create_sef_url_indicator, // Should SEF URLs be returned? true or false
    $runtime_data->parameters->display_index_in_url_indicator, // Should index.php appear in the URL? true or false

    // Primary Data: the total number of rows that could have been returned for the primary data
    $data_instance->getTotalItemsCount(),

    // Router: data from your router to help build the URLs for the pagination links
    $runtime_data->route->page, // URL for page on which paginated appears
    $runtime_data->route->parameter_start, // Query parameter 'start', for example, "?start=3" or "/start/3"
    array() // Other query parameters like "&tag=dog" or "/category/dog"
);

```

### Pagination Output

The Pagination `getPaginationData` method returns a data object of the following data elements that
correspond to this mock-up of a rendered view.

```
   << << << 1 2 3 4 5 >> >> >>
   A ... B. C........ D. E....

```

A `<< <<`

* $row->first_page_number
* $row->first_page_link

B `<<`

* $row->previous_page_number
* $row->previous_page_link

C  Used to loop through page links `1 2 3 4 5`

* $row->start_links_page_number
* $row->stop_links_page_number
* $row->page_links_array

D `>>`

* $row->next_page_number
* $row->next_page_link

E `>> >>`

* $row->last_page_number
* $row->last_page_link

Additional data provided by the method:

* $row->current_start_parameter_number
* $row->current_start_parameter_link
* $row->total_items

### Example View

The following View is [part of the Example](https://github.com/Molajo/Pagination/blob/master/.dev/Sample/Views/Pagination.phtml)
to demonstrate how to use the pagination data for rendering.

```php

<nav>
    <ul class="pagination">
        <?php if ((int)$row->first_page_number == (int)$row->current_start_parameter_number) : ?>
            <li>&laquo; &laquo;</li>
        <?php else : ?>
            <li><a href="<?= $row->first_page_link; ?>">&laquo; &laquo;</a></li>
        <?php endif; ?>

        <?php if ((int)$row->previous_page_number == (int)$row->current_start_parameter_number) : ?>
            <li>&laquo;</li>
        <?php else : ?>
            <li><a href="<?= $row->previous_page_link; ?>">&laquo;</a></li>
        <?php endif; ?>

        <?php
        for ($i = $row->start_links_page_number;
             $i < $row->stop_links_page_number + 1;
             $i ++) {
            if ((int)$i == (int)$row->current_start_parameter_number) : ?>
                <li class="current">
            <?php else : ?>
                <li>
            <?php endif; ?>
            <a href="<?= $row->page_links_array[$i]; ?>"><?= $i; ?></a></li>
        <?php } ?>

        <?php if ((int)$row->next_page_number == (int)$row->current_start_parameter_number) : ?>
            <li>&raquo;</li>
        <?php else : ?>
            <li><a href="<?= $row->next_page_link; ?>">&raquo;</a></li>
        <?php endif; ?>

        <?php if ((int)$row->last_page_number == (int)$row->current_start_parameter_number) : ?>
            <li>&raquo; &raquo;</li>
        <?php else : ?>
            <li><a href="<?= $row->last_page_link; ?>">&raquo; &raquo;</a></li>
        <?php endif; ?>
    </ul>
</nav>

```


## Install using Composer from Packagist

### Step 1: Install composer in your project

```php
    curl -s https://getcomposer.org/installer | php
```

### Step 2: Create a **composer.json** file in your project root

```php
{
    "require": {
        "Molajo/Pagination": "1.*"
    }
}
```

### Step 3: Install via composer

```php
    php composer.phar install
```

## Requirements and Compliance
 * PHP framework independent, no dependencies
 * Requires PHP 5.4, or above
 * [Semantic Versioning](http://semver.org/)
 * Compliant with:
    * [PSR-1](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md) Basic Coding Standards
    * [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) Coding Style
    * [PSR-4](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md) Coding Standards
 * [phpDocumentor2] (https://github.com/phpDocumentor/phpDocumentor2)
 * [phpUnit Testing] (https://github.com/sebastianbergmann/phpunit)
 * Author [AmyStephen](http://twitter.com/AmyStephen)
 * [Travis Continuous Improvement] (https://travis-ci.org/profile/Molajo)
 * Listed on [Packagist] (http://packagist.org) and installed using [Composer] (http://getcomposer.org/)
 * Use github to submit [pull requests](https://github.com/Molajo/Pagination/pulls) and [features](https://github.com/Molajo/Pagination/issues)
 * Licensed under the MIT License - see the `LICENSE` file for details
