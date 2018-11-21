# EQS REST TEST
This is the **BILL THE INVESTOR** REST-Based Application for the EQS Test.
This Application is built upon Laravel 5.7 and uses the Bootstrap CSS Framework in the Frontend 
as well as some Compass-baked Custom Styles.

The App exposes a Navigation Menu and has 3 Main Pages, each corresponding respectively to the Mock-Up Designs in the Test-Package.  
The Routes are equally named in a similar fashion. This App doesn't not provide any form of Identity Authentication such as
Login Screens, etc. It just works straight out-of-the-Box. 

The App uses the non-paid Version of 3rd-Party Service: __INTRINO__ as the preferred API Provider.
Whereas this is well appreciated, there are observable limitations to this API - but, who is to question, what has be freely granted?

## INSTALLATION + USAGE HINTS
Simply run `composer install` on the cloned package.
Once Composer finishes installing all the necessary Dependencies, run `php bin/artisan serve --port=8080`.
You may choose a Free Port on your System if 8080 is already occupied or you may equally serve it directly from your 
Local Web-Server rather than using the built-in PHP-Server. That option is left to you ;-)
That's all! Afterwards, you may want to navigate to `http//:localhost:8080` - assuming you went that route.


##### IMPORTANT NOTES

- [ ] An uncompleted task
- [x] A completed task


First Header | Second Header
------------ | -------------
Content from cell 1 | Content from cell 2
Content in the first column | Content in the second column


Please note that you may experience slower loading times. 
This is partly due to the fact that no Caching was implemented for this Solution and also 
because I am using the unpaid Version of Intrino REST API (which, not unexpectedly has drawbacks).

The `Price Entered Time` in the __Market Overview__ page is not dynamic and thus also kind of a Hack. 
It is randomly generated. This boils down to the Limitations I mentioned earlier as well as my not having 
prior experiences with Finance-Sector API & so forth...

Most of the Data in the Payload have `Ordinary Shares` Security-Type so i decided to assign Blue Backgrounds to 
all that have any other Security-Type other than Ordinary Shares - perhaps for my convenience. 

As already implied; I've never worked in the Financial Sector before, however; finding the right Service/API was much more tasking than
implementing the Solution... the Intrino API i used is not all that comprehensive. This means; if i would have to
rightly implement the __Company Stock Overview Page__, I'd have to perform multiple API Calls and then manually sort the Data 
(since Such Dataset isn't available on the Intrino Platform - or perhaps, i didn't know where to look).
This wouldn't be a problem at all except that this is Test with a Time-Limit and a waste of Time would help no-one. 
On the other hand, i believe that in reality, such Data are very much available to the Developer or at least something closer.

What i have here is the best i can get using the DataSet provided by Intrino (an unpaid Service). Perhaps, I could have
researched more but it is my belief that for the Purposes of mere Coding-Test, this might be suffice. 
The entire Process took around 3 - 4 Hours Max, although I'm aware I have up to 8 Hours but it also happens that 
i have quite a lot to do today so i decided to just push this as it is.... However, if you insist on a perfect + exact implementation,  
I'd ask that you let me do it by Monday as I'd be less pressed for Time, then. 

Please note that this App exposes no Test - functional or unit!

A couple of Screenshots were also added to the top-level Package: in the `-SCREENSHOTS-` Folder. 

