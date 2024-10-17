
/*
-----------------------------------------------------------
This file originally created by James Sleeman of Gogo Code.
This javascript is used to auto-generate examples

Modified for use with AreaEdit plugin chooser demo.
-----------------------------------------------------------
*/

var num = 1;

if ( window.parent && window.parent != window)
	{
	var f = window.parent.menu.document.forms[0];
	num = parseInt(f.num.value);
	if (isNaN(num) )
		{
		num = 1;
		f.num.value = 1;
		}
	areaedit_plugins = [ ];

	for(var x = 0; x < f.plugins.length; x++)
		{
		if (f.plugins[x].checked ) 
			areaedit_plugins.push(f.plugins[x].value);
    }
  }

areaedit_editors = [ ]

for(var x = 0; x < num; x++)
	{
	var ta = 'myTextarea' + x;
	areaedit_editors.push(ta);
	}

areaedit_config = function()
	{
	var config = new HTMLArea.Config();

	if (typeof CSS != 'undefined')
		{
		config.pageStyle = "@import url(custom.css);";
		}

	if (typeof Stylist != 'undefined')
		{
		// We can load an external stylesheet like this - NOTE : YOU MUST GIVE AN ABSOLUTE URL
		//  otherwise it won't work!

		config.stylistLoadStylesheet(document.location.href.replace(/[^\/]*\.php/, 'stylist.css'));

		// Or we can load styles directly

		config.stylistLoadStyles('p.red_text { color:red }');

		// If you want to provide "friendly" names you can do so like
		// (you can do this for stylistLoadStylesheet as well)

		config.stylistLoadStyles('p.pink_text { color:pink }', {'p.pink_text' : 'Pretty Pink'});

		}

	if ( typeof DynamicCSS != 'undefined' )
		{
		config.pageStyle = "@import url(dynamic.css);";
		}

	return config;

	}

var f = document.forms[0];
f.innerHTML = '';

var lipsum = document.getElementById('lipsum').innerHTML;

for(var x = 0; x < num; x++)
	{
	var ta = 'myTextarea' + x;

    var div = document.createElement('div');
    div.className = 'area_holder';

    var txta = document.createElement('textarea');
    txta.id   = ta;
    txta.name = ta;
    txta.value = lipsum;
    txta.style.width="100%";
    txta.style.height="420px";

    div.appendChild(txta);
    f.appendChild(div);
  }
