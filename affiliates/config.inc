GIF89a  ч       €ДДД∆∆∆€)Ф€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€!щ   ,       w 	@∞†AБ	(ј∞!C  0±"E Dƒи∞a Ж B^іhqА…BN‘ш±"√()hЙ@JШ8C≤9gLЪ)јіy2eMЪ?6Џ±!—Ь#£*}q`GУEЂ*lк0%BX√Ж’
Q§Y≥ ;                                                                                                      d cart are on completely
// different domains.  For example, you might have http://www.example.com for
// your store, and https://example.mysecureserver.com for your checkout.  This
// configuration will not work.

$cookie_domain = '';

// The currency symbol and SWIFT code:

$currency = '$';
$currency_code = 'USD';

// This should be set to the base of your Magento installation:

$store_home = 'http://www.example.com/store';

// You can give a fixed amount of commission per order, or you can give a
// percentage.  If you give both, they add.  Set these variables to the
// percentage rate and the fixed amount which you want to pay to your
// affiliates:

$commission_percent = 10;
$commission_fixed = '1.00';

// Set this to the name of your affiliate programme.  It will be shown at the
// top of all the screens used by affiliates:

$affiliate_programme_name = 'Spotlight Home Tour\'s Affiliate Program';

// If you want affiliates to share revenue for all the orders their customers
// place, set the following to TRUE.  If you only want them to receive revenue
// share when customers have an affiliate cookie set on their browsers, set
// it to FALSE.
//
// For example, suppose an affiliate introduces customer X.  X places an order
// and the affiliate receives commission.  After that, X changes browser,
// meaning that he no longer has an affiliate cookie set.  He then places
// another order.  If this variable is TRUE, the affiliate receives commission
// on this order too (provided X uses the same account of course).  If this
// variable is FALSE, he does not.

$lifetime_revenue_share = TRUE;


// If you want to be notified when AfA accepts an order, or marks an order
// shipped, enter an email address here:

$notification_email_address = 'example-notify@affiliatesforall.org';

// If you don't want these notifications, leave the email address out, by
// changing the above line to read:
// $notification_email_address = '';


// The following address is used as the sender for these email notifications,
// and also for email notifications sent to affiliates:

$administrator_email_address = 'example-admin@affiliatesforall.org';

// You *must* change this to an email address which you control, as
// you will not be considered a legitimate mail sender for
// affiliatesforall.org.  As a result many mail servers would consider your
// notifications to be spam.


// Affiliates can be given full or restricted information about the referred
// customers and their orders.  The fields you want to reveal should be placed
// in this list:

$order_fields_available =
    'id, status, customer_id, customer_name, customer_email, total, ' .
    'commission, date_entered, affiliate_data';
$order_fields_headings =
    'Order Number, Status, Cust ID, Cust Name, Cust Email, Total, ' .
    'Commission, Order Date, Campaign Data';

// The following secret is used to secure communications between the shopping
// cart and the affiliate system.  Just choose something thatGIF89a  ч       ДДДДп €€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€€!щ   ,       e  @∞†AГX»∞бB 	JpР БЛ1Z‘@А«Л-R<ШсвF -~$ еHГM д(А•ЋЫЏDyrf Э-s2d…Rа@Я!&,(TІR£FQЦ|
u`E• ;                                                                                                                        followed by a newline (it cannot be the
// last line of the file).
