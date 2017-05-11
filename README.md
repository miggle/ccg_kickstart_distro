# NHS CCG Distribution

## Installing the site
Download the distribution from the list of releases [here](https://github.com/miggle/ccg_kickstart_distro/releases)  
You will now have a version of Drupal 8 that can be installed as you normally would.

_If you're new to this process you will need to follow these steps:_  
1. [Create a database](https://www.drupal.org/docs/8/install/step-3-create-a-database)  
2. [Configure the installation](https://www.drupal.org/docs/8/install/step-4-configure-your-installation)  
3. [Run the installer](https://www.drupal.org/docs/8/install/step-5-run-the-installer)

When running the installer there will be a step that allows you to configure the features that you want to use
to build the your website.

## Maintaining the site
The distribution is managed via [Composer](https://getcomposer.org), you will need to have Composer installed in order
to continue maintaining the site in this way.  
If you would prefer to manage your site in another way that's perfectly fine, but we'll just be covering the process of
using Composer here.

### Updates
To update site dependencies you will need to navigate to the project root in a terminal window.  
From here you can run:  
```composer update```  
This will update all modules and libraries required by the CCG Kickstart profile to the latest release according to the
version requirements specified.

### Adding new modules
To add a new module to the project, again, navigate to the project root in a terminal window and run:  
```composer require drupal/[module_name]:[version]```  
With `[module_name]` being the name of the module and `[version]` being the version requirement.  
See the [Composer documentation](https://getcomposer.org/doc/articles/versions.md) for more information on versions.

## Installing new features from updates
If you already have a site installed using the distribution, when updating to the latest release that introduces new 
features, these features won't be enabled automatically.  
In order to enable new feature you will need to enable the module manually. Either through the administration pages
or via drush.

## Reporting bugs
If you come across any issues while installing the distribution or using the features that it provides please create
 an issue in the GitHub project issue queue.
