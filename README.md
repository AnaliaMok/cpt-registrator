[![Build Status](https://travis-ci.com/AnaliaMok/cpt-registrator.svg?branch=master)](https://travis-ci.com/AnaliaMok/cpt-registrator)

# CPT Registrator

A framework geared towards the more programmatic WordPress developer. The main purpose of the framework is to help eliminate the need to copy and paste code from online generators and to eliminate repeated code in general.

Learn more on how to use this framework in the [wiki](https://github.com/AnaliaMok/cpt-registrator/wiki/Basic-Usage)

## Goals:

1. Create a simple "framework" for auto-registering CPTs in Wordpress without having to remember a `require_once` statement in either your `functions.php` file or some "single source of truth" type file.

2. Reduce tedium and code duplication found when defining CPT arguments.

## Tasks

- [x] Create base class for **defining** a custom post type
- [x] Create a base class for defining taxonomies (Need to document)
- [x] Implement single file method for defining all cpts in a site. (Need to document)
- [x] Create class autoloader
- [ ] (**Reach Goal**) Add ability to define a "parent" CPT. This parent will act as a top level menu item which will then direct to a dashboard-like admin page. The page will be used to then display all child CPT records.
- [ ] (**Reach Goal**) Add extension for use with ACF and CMB2
