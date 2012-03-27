SilverStripe ModifiedTableField
===================================

LinkField

Maintainer Contacts
-------------------
* Nathan Cox (<nathan@flyingmonkey.co.nz>)

Requirements
------------
* SilverStripe 2.4+

Documentation
-------------
[GitHub Wiki](https://github.com/nathancox/silverstripe-linkfield)

Installation Instructions
-------------------------

1. Place the files in a directory called linkfield in the root of your SilverStripe installation
2. Visit yoursite.com/dev/build to rebuild the database

Usage Overview
--------------

Define the database field as a string:

static $db = array(
	'URL' => 'Varchar(255)'
);

Add the LinkField like you would with a text field

function getCMSFields_forPopup() {
	$fields = new FieldSet();
	
	...
	
	$fields->push(new LinkField('URL', 'URL'));
	
	return $fields;
}

The value is saved using the [sitetree_link] shortcode if you pick a page on your side, so the value needs to be parsed before being outputted (eg in a template):

function getLink() {
	return LinkField::link_url($this->URL);
}


Known Issues
------------
[Issue Tracker](https://github.com/nathancox/silverstripe-linkfield/issues)