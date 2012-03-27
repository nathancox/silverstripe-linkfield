SilverStripe LinkField
===================================

LinkField creates a composite field with radio buttons that let the user set a URL by choosing between a text field and a site tree dropdown fields.  Like the fields on a RedirectorPage, but packaged in to one field object for convenience.

It stores the result as a string (eg to a Varchar).  If the user chooses a page from the dropdown it's stored as a shortcode like the ones used in the content when linking to an internal page with TinyMCE.  Eg:

[sitetree_link id=2]

The problem is that means the database field could contain either a URL or a shortcode, so output needs to be parsed by the shortcode filter, which can be done with LinkField::link_url($this->URL);


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

```
static $db = array(
	'URL' => 'Varchar'
);
```

Add the LinkField like you would with a text field

```
function getCMSFields_forPopup() {
	
	...
	
	$fields->addFieldToTab('Root.Content.URL', $urlField = new LinkField('URL', 'URL to link to'));
	$urlField->setConfig(array(
		'localLabel' => 'A page on this site',
		'remoteLabel' => 'Another site or URL'
	));
	
	...
	
	return $fields;
}
```

The value is saved using the [sitetree_link] shortcode if you pick a page on your side, so the value needs to be parsed before being outputted (eg in a template):

```
function getLinkedURL() {
	return LinkField::link_url($this->URL);
}
```

Known Issues
------------
[Issue Tracker](https://github.com/nathancox/silverstripe-linkfield/issues)