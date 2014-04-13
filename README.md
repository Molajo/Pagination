
=======
Molajo Pagination
=======

[![Build Status](https://travis-ci.org/Molajo/Pagination.png?branch=master)](https://travis-ci.org/Molajo/Pagination)

Data object ArrayIterator and easy pagination for PHP, framework independent.

## At a glance ...

1. Get the `page_url`, `query parameters` and `page` value from your preferred `Http Request Class.`
1. Run a query (or produce a list of items) using normal `offset` and `row limit` criteria.
2. Instantiate the `Pagination` class, injecting it with the data and various pagination values.
3. Use the pagination object to build the data needed to render the pagination interface.

```php

    /** From Http Request Class */
    $page_url = 'http://example.com/staff';
    $query_parameters = array('tag' => 'celebrate'); // Exclude the page parameter
    $page = 1;

    /** From Database Query */
    $data = $this->database->execute($query);
    $total_items = 15;

    /** Application Configuration */
    $per_page = 3;          // How many items should display on the page?
    $display_page_link_count = 3;     // How many numeric page links should display in the pagination?

    /** Instantiate the Pagination Adapter */
    use Molajo\Pagination\Adapter as Pagination;
    $pagination = new Pagination(
        $data,
        $page_url,
        $query_parameters,
        $total_items,
        $per_page,
        $display_page_link_count,
        $page
        );

    /** Data: use pagination object as ArrayIterator */
    foreach ($pagination as $item) {
        include __DIR__ . '/' . 'TemplatePost.php';
    }

    /** Pagination: getPageUrl, getStartDisplayPage and getStopDisplayPage methods */
    <footer class="pagination">
        <a href="<?php echo $pagination->getPageUrl('first'); ?>">First</a>
        &nbsp;<a href="<?php echo $pagination->getPageUrl('prev'); ?>">«</a>
        <?php
        for ($i = $pagination->getStartDisplayPage(); $i < $pagination->getStopDisplayPage(); $i++) { ?>
            <a href="<?php echo $pagination->getPageUrl($i); ?>"><?php echo $i; ?></a>
        <?php
        } ?>
        <a href="<?php echo $pagination->getPageUrl('next'); ?>">»</a>
        &nbsp;<a href="<?php echo $pagination->getPageUrl('last'); ?>">Last</a>
    </footer>
```


### Working Example

A working example of a Pagination View is in the
[.dev/Sample/ Folder](https://github.com/Molajo/Pagination/tree/master/.dev/Example). To see the demo
on your local website, create an Apache Host using
[the Public Folder](https://github.com/Molajo/Pagination/tree/master/.dev/Sample/Public) as the Disk Location.
Then, use the Server Name as the address in your browser.

The working example demonstrates how to use the pagination with an Http Request and database simulation. You
don't have to hook it up to your database, the example works right out of the box with only the example files.
The code is well documented in order to help you get up and running quickly.

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
    * [PSR-0](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md)
    * [PSR-1](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md) Namespacing
    * [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) Coding Standards
 * [phpDocumentor2] (https://github.com/phpDocumentor/phpDocumentor2)
 * [phpUnit Testing] (https://github.com/sebastianbergmann/phpunit)
 * Author [AmyStephen](http://twitter.com/AmyStephen)
 * [Travis Continuous Improvement] (https://travis-ci.org/profile/Molajo)
 * Listed on [Packagist] (http://packagist.org) and installed using [Composer] (http://getcomposer.org/)
 * Use github to submit [pull requests](https://github.com/Molajo/Pagination/pulls) and [features](https://github.com/Molajo/Pagination/issues)
 * Licensed under the MIT License - see the `LICENSE` file for details
