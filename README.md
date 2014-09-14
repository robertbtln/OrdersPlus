## Order Plus

**Short Version:**

**OrdersPlus** is a *Magento* module that was originally made to extend the functionality of the Magento Orders Grid (_**Sales** > **Orders**_). It has evolved from that into so much more and **OrdersPlus** has gained so much more functionality.

**Long(er) Version:**

The standard Magento Orders Grid is regarded by many to be a little lacking when it comes to information about orders. By default, you would need to go into each order to get some important pieces of information. This module lets the admin of the shop control extra pieces of information for that grid. You can enable extra columns to show on the orders grid such as Order Information (products, skus & quantities ordered), payment type, various customer information, etc. You can also disable default columns if you believe they’re not needed.

**Here is a list of what this module has to offer:**

### Options

Options to hide some of the columns:

#### *Default* columns
* Purchased From
* Bill to Name
* Ship to Name
* Grand Total (Purchased)
* Action

_**Adds new columns:**_

#### Order Breakdown Columns
* Discount Code
* Payment Method
* Product Information
* Product Information: Number of Products to Show
* Number of products to show (up to) in order grid.
* Customer Comment
* Shows customer comment from box on checkout page
* Shipping Method
* Total Expected Package Weight

#### Customer Information Columns
* Customer Email
* Shipping (to) Street Address
* Shipping (to) City
* Shipping (to) State/Region
* Country ID
* Shipping (to) Zip/Postal Code
* Customer (Ship to) Phone Number

#### Sales Information _(NEW)_
* Customer Lifetime Sales Data (Sales Order Overview)
* Applicable for logged in customers only.
* Stock level (Sales Order Overview Page - Items Ordered table)
* Quantity Ordered - in parenthesis (Sales Order Overview Page - Items Ordered table)
* Stock level (Create Order Product Grids)

As was mentioned in the _Order Breakdown_ area, this module adds commenting functionality for customers during checkout. You can read the comment from the customer in the _Sales Orders Grid_ or in it's standard place within the order.

**Sales Information _(NEW)_** extends the functionality past the _Sales Orders Grid_ and now adds useful information on the _Order Overview_ page and on the **Create Order** grids.

## Versions Tested

- MAGENTO CE 1.5.1.0
- MAGENTO CE 1.6.2.0
- MAGENTO CE 1.7.0.2
- MAGENTO CE 1.8.0.0

## Instructions

Please backup your site and test on a test site/server first!

These instructions are for the infamous (well, um…) Orders Plus module.

1. Retrieve the module code from Github however you desire.
2. Upload (via FTP client, such as [FileZilla](http://filezilla-project.org/)) the **app** directory to the root of your Magento install.
3. If you're logged into your admin panel, please log out and then log back in. Otherwise you will get that pesky _**404, not found**_ message when trying to view the **config** area.

Then navigate to

```bash
System >  Configuration > [GSC Modules] Orders Plus
```

for additional options. Once there you'll see the following tabs

- Sales Grid Columns: Original (Default) Columns
- Additional Grid Columns: Order Breakdown Columns
- Additional Grid Columns: Customer Information Columns
- OrdersPlus Main Option Tabs

![](http://www.graphicsourcecode.com/wp-content/uploads/2012/09/ordersplus-screen1.jpg)

**Here's a general description of each tab:**

- **Sales Grid Columns: Original (Default) Columns:** This gives you enabling/disabling options for certain default columns in the sales grid.
- **Additional Grid Columns: Order Breakdown Columns:** This gives you enabling/disabling options for certain columns in the sales grid that have to do with the order itself. For instance, you can choose to enable payment method, product information or shipping method (to name a few).
- **Additional Grid Columns: Customer Information Columns:** This gives you enabling/disabling options for certain columns in the sales grid that have to do with the customer. For instance, you can choose to enable customer email, country id or phone number (to name a few).
- **Note:** all of the above options are _searchable_.

The titles are pretty self explanatory so I won't go into each one. The ones that need it have a comment below explaining what it is for. And the "switches" are pretty easy too! **Enable** with turn on (_show_) the column in the sales grid, while **disable** will turn the column off (_hide it_).

Once configured the way you want, head to **Sales** > **Orders** to view the grid and reap the benefit of all the extra information.

**Please note:** you will have to scroll to the right on the sales grid if you have more than the default number of columns showing.
