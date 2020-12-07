# MailPlus Connector

Connect your webshop to your MailPlus account.

NOTE: This version is for Magento 2.2 or higher. A MailPlus eCom account is required to utilize the following features. 
Please contact MailPlus to setup your account.

## Installation using composer

- cd \<magento-folder\>

- composer require mailplus/mailplus-connector

- bin/magento module:enable MailPlus_MailPlus

- php bin/magento setup:upgrade

- php bin/magento setup:di:compile



## Connects automatically with your webshop

- Contact details are automatically synchronized with MailPlus. So you are always ready for a new mailing.
- All products are synchronized to MailPlus. So you can simply add the products to your newsletter.
- In eCom Business & Enterprise all orders are synchronized which you can use for RFM selections.

## Email editor with product catalog

- Select a product from the catalog. MailPlus places it directly in the newsletter with your own design.
- Show the number of rating stars per product. This information is automatically synchronized with your webshop. 
- Let your recipients easily share products in your newsletter via Twitter, Facebook, Google+ and LinkedIn.
- Follow the behavior of your recipients outside your newsletter with the automatically added analytics code.

## Clear reports

- Next to the mailing statistics you will also see a report of the conversion in your store as a result of the mailing. 
- A unique feature is the possibility of comparing the response of multiple similar e-mails.

## Ready-to-use automatic campaigns (eCom Business &Enterprise)

- Abandoned cart: Send your customers an automatic message when they abandon their shopping cart.
- Win-back: Send a special offer to customers who haven't placed any order in the last 6 months.
- Birthday: Encourage sales and involvement with a personal birthday greeting.
- Product review: Easily collect valuable reviews for more conversion and better search engine visibility.
- Welcome: A registration confirmation will be sent automatically to new subscribers to your newsletter.

## Target Selections based on order history

- Find out who are your most loyal customers with a selection on the number of orders. 
- Who are the 'big spenders'? Make a selection in MailPlus based on revenue. 
- Make selections based on a product or brand. Useful for a recall or special offer.

## Project development initialization

- Run composer install
	*	This should install dependencies to ../vendor (Intellij should add these as library roots for code completions
	* 	You might need to create a app/etc/vendor_path.php file
- Run vagrant up
- Uncomment the synced folders in the vagrant file and vagrant reload (you can move the vagrantfile to a separate change set so it's not committed in git)
- Enable the plugin by running (in the vagrant box)

	magento module:enable --clear-static-content MailPlus_MailPlus
	
	magento setup:upgrade
	
	magento setup:di:compile
