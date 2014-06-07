YiMaSkeletonApplication
=======================

Introduction
------------
This is a skeleton application using the ZF2 MVC layer and module systems.

Installation
------------

Using Composer (recommended)
----------------------------
The recommended way to get a working copy of this project is to clone the repository
and use `composer` to install dependencies using the `create-project` command:

    curl -s https://getcomposer.org/installer | php --
    php composer.phar create-project -sdev --repository-url="https://packages.raya-media.com" rayamedia/yima-skeleton path/to/install

Alternately, clone the repository and manually invoke `composer` using the shipped
`composer.phar`:

    cd my/project/dir
    git clone git@github.com:YiMAproject/yimaSkeleton.git
    cd yimaSkeleton
    php composer.phar self-update
    php composer.phar install

(The `self-update` directive is to ensure you have an up-to-date `composer.phar`
available.)

Another alternative way is to downloading the project from github page.

You would then invoke `composer` to install dependencies per the previous
example.
