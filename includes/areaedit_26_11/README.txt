
   AreaEdit - Browser Based WYSIWYG HTML Editor Component.

by: Yermo Lamers of DTLink,LLC
http://www.formvista.com/contact.html

AreaEdit   is  a  formVista  compatible  fork  of  the  rapidly
developing  Xinha  project.  Xinha  itself  is  a  fork  of the
original   HTMLArea   component  created  by  Mihai  Bazon  and
sponsored  by Interactivetools. AreaEdit maintains the original
HTMLArea (modified BSD) license and is free to use.

While   it   is  designed  to  work  within  formVista  without
modification, AreaEdit does not requre formVista. It is a fully
functional  editor component supporting a number of plugins and
can  as  easily  be used standalone in any PHP web application.
Additionally,  if  the  ImageManager,  Linker  and SpellChecker
plugins are not needed then AreaEdit can be used independent of
any backend language.

The  primary  difference  between  AreaEdit and Xinha is one of
focus.  The  Xinha  project  aims  to  be  a rapidly developing
feature-filled  editor  with many plugins worked on by a larger
number  of  developers.  It  also  aims to be backend agnostic.
(i.e.  showing  no  preference  over PHP, Perl, ASP, etc on the
backend.)

By  contrast,  AreaEdit takes a slower minimalists approach. It
aims  first and foremost to be functional and maintainable. The
feature  set  we  are  focusing  on is targeted squarely at the
serious  non-technical business user. Business users don't care
about the nuances of HTML <br> and <p> tags, they just want the
editor  to  work  as  they  would  expect.  Additionaly,  those
features  of  AreaEdit  that  require  backend  support such as
Imagemanager,  Linker  and SpellChecker are implemented in PHP.
While  this  does  not  preclude  others  from developing those
backends  in  other languages (hooks are available), we are not
likely  to that development here unless we have a paying client
funding the effort.

If  your  audience  is primarly composed of developers or those
familar with HTML, you may wish to take a look at Xinha.

Please see the CREDITS file.

AreaEdit  is  released  under  the  same terms as HTMLAREA. For
license  information  please  see  the accompanying LICENSE.txt
file. It is also available on the web at:

  http://www.formvista.com/otherprojects/areaedit/license.html

For  questions,  suggestions  and  bug  reports  please use the
formVista discussion forums at:

  http://www.formvista.com/forum.html

---------------------------------------------------------------

REQUIREMENTS:

AreaEdit  works in MSIE 5.5 or later and all modern Gecko based
browsers.

If you want to run the examples or are going to use any plugins
that  require  server  backend support you will need a properly
configured  webserver  that  supports PHP. In addition you will
need a command line PHP interpreter in order to run the command
line   Configure.php   script.   (Configure.php  generates  the
backends/backend_conf.php file).

The  AreaEdit  codebase  is  developed under Linux and has only
been  tested  on that platform. It should work under Windows as
well.  If  try  to  run  it under Windows and have any problems
please report them to us.

---------------------------------------------------------------

AreaEdit  used to be known as the Xinha unified backend branch.
The  main  difference between it and standard Xinha is that all
client  to  server  communications  are routed through a single
backend  script  (backends/backend.php). This design allows the
component to work within the formVista framework in addition to
standalone.

JavaDoc  style  headers  have  been  added  to  all  methods in
addition to extensive debugging trace messages.

A  number of scripts have been being added to make the codebase
more manageable. These scripts require Perl and PHP.

See   the  README_DEVELOPERS.txt  for  more  information  about
working with the codebase.

For more information see:

  INSTALL.txt
  README_DEVELOPERS.txt
  index.html
  docs/index.html
  http://www.formvista.com/otherprojects/areaedit.html
  
--------------------------------------------------------------
On the Subject of <p> vs. <br> tags on Enter:

By default Gecko based browsers insert a <br> tag when ENTER is 
pressed. By contrast, MSIE wraps text correctly in <p> tags.

As a result, both the generated HTML and the user experience are
different under the two browser. In MSIE, ENTER behaves like a 
double enter when <p> tags have their default style. 

The "correct" solution is to have both browsers use <p> tags and then
modify the look from stylesheets.

EnterParagraphs is a plugin that valiently attempts to correctly generate 
wrapped <p> tags in FireFox. Unfortunately, even after countless hours 
of work it's still largely very very broken.

For the time being, AreaEdit uses the default browser behavior on ENTER. 
Under FireFox, EnterParagraphs intercepts CNTRL-ENTER and does it's thing. 
(see areaedit/plugins/EnterParagraphs/enter-paragraphs.js line 940).

Once we can get EnterParagraphs working in all cases correctly we'll 
change the behavior back to onENTER.
