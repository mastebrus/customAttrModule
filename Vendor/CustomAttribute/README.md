This module assigns a text value for a custon product attribute.
Contains files needer for module creation and registration (module.xml and registration.php).
A composer.json file that defines module into your local composer repository for good practice.
In etc/config.xml i define the module to appear in adminhtml page to be seen in the "GENERAL" Tab in the stores-> configuration

etc/system.xml file defines the enable disable functionality in a field "enable" that can be set to yes or no in every scope (default, website, store)
 
Model/Config.php fetchs and uses the config value assigned in the admin tab for the module.

You can enable the module via bin/magento module:enable vendor:customattribute.
Or bin/magento setup:upgrade if oyu are in development mode.

You can run the module by typing  bin/magento vendor:customattribute:update <text-value>

The definition of the command line command its in the etc/di.xml
This di.xml file charges all dependencies needed and calls the UpdateProductAttribute.php
which contains two methos one that defines the commandline command and the other that executes it.

Setup/InstallData.php file is part of the setup scripts used during the installation or upgrade process of the  module to add this new custom attribute.

In the view/frontend  folder we have the layout config for the catalog_product_view.xml to see the custom attribute added to the product page.
The validation.js that validates if the attribute is not emtpy or null.
The view.phtml template file we can see defined the html layout for the custom attribute and a validation to show error if its shorter than 3.


