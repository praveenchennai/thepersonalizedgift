<?

/*
--- USAGE ---
include "form.inc.php";

// Creating the new Form
// Parameters are: <title>, <form name>, <FORM_METHOD_POST|FORM_METHOD_GET>, <action>
$form = new Form("Admin Login", "myform", FORM_METHOD_POST, "postHandler.php");



// Adding three different groups of fields
// Parameters for addGroup are: <group internal name>, <group title>
$form->addGroup			("personal",		"Personal Information");
$form->addGroup			("contact",			"Contact Information");
$form->addGroup			("other",			"Others");

// Adding some fields to the groups
// Parameters for addElement are: <group internal name on wich to include>, <element object>
// Where <element object> could be one of these, with its sintaxes:
// FormElementMessage		(<field message>, <CSS style name>)
// FormElementText			(<field title>, <input name>, <default value>, <CSS style name>, <length>, <maxlength>, <0|1 Is or not a read-only field>)
// FormElementOnlyText		(<field title>, <text to show>, <CSS style name>)
// FormElementPassword		(<field title>, <input name>, <default value>, <CSS style name>, <length>, <maxlength>)
// FormElementCombo			(<field title>, <input name>, <default value>, <CSS style name>, <combo size>, <array of values>, <0|1 Whether or not this combo allows multiple selections>) Where <array of values> is a hash-array like array ("title" => "value", "title" => "value" ...)
// FormElementRadio			(<field title>, <input name>, <default value>, <CSS style name>, <combo size>, <array of values>) Where <array of values> is a hash-array like array ("title" => "value", "title" => "value" ...)
// FormElementCheckbox		(<field title>, <input name>, <default value>, <CSS style name>)
// FormElementTextarea		(<field title>, <input name>, <default value>, <CSS style name>, <number of columns>, <number of rows>)
// FormElementHidden			(<input name>, <value>)

$form->addElement		 (FORM_GROUP_MAIN, 		new FormElementMessage	("There is Error", 	"naError"));

$form->addElement		("personal",			new FormElementText		("name",			"name",				"",				"forminput",			20,				255,	0));
$form->addElement		("personal",			new FormElementText		("appearence",		"appearence",		"",				"forminput",			40,				255,	0));
$form->addElement		("personal",			new FormElementCombo	("sex",				"sex",				"M",			"formcombo",			0,				array ("F" => "Female", "M" => "Male"), 1));

$form->addElement		("contact",				new FormElementRadio	("Contact Method",	"contact_method",	"1",			"formradio",			0,				array ("0" => "Telephone", "1" => "EMail")));
$form->addElement		("contact",				new FormElementText		("Number",			"number",			"",				"forminput",			20,				255,	0));
$form->addElement		("contact",				new FormElementCheckbox	("Fax",				"fax",				true,			"formcheckbox"));

$form->addElement		("other",				new FormElementTextarea	("Description",		"description",		"",				"formtextarea",			40,				3));
$form->addElement		("other",				new FormElementText		("Other 1",			"other1",			"",				"forminput",			20,				255,	0));
$form->addElement		("other",				new FormElementText		("Other 2",			"other2",			"",				"forminput",			20,				255,	0));

// Adding a hidden field
$form->addElement		(FORM_GROUP_HIDDEN,		new FormElementHidden ("a", "value for a"));
// Showing the form
echo $form->getform ();

// There are a few public variables inside the object form that could be changed,
// This ones are self-explaining: title_bgcolor, title_style, group_bgcolor, group_style, element_bg_style, element_style, submit_bgcolor, submit_title, submit_style, reset_title, reset_style
// And this others:
// element_separator: Contains whatever HTML code which will be included to separate the field names from the values.
// issubmit: Wether or not to show the submit button
// isreset: Wether or not to show the reset fields button

*/

define ("FORM_METHOD_GET",		"GET");
define ("FORM_METHOD_POST",		"POST");

define ("FORM_GROUP_MAIN",		"%%main%%");
define ("FORM_GROUP_HIDDEN",	"%%hidden%%");

class FormGroup
{
	var			$name;
	var			$title;

	function FormGroup ($name, $title)
	{
		$this->name		= $name;
		$this->title	= $title;
	}
}

class FormElement
{
	var			$title;
	var			$name;
	var			$value;

	function FormElement($title, $name, $value)
	{
		$this->title	= $title;
		$this->name		= $name;
		$this->value	= $value;
	}
}

class FormElementMessage extends FormElement
{
	var	$style = "formText";

	function FormElementMessage($message, $style)
	{
		$this->FormElement($message, "", "");
		if ($style != "") $this->style = $style;
		$this->colspan = true;
	}

	function getTag ()
	{
		return "<span class=\"".$this->style."\">".$this->title."</span><br>";
	}
}

class FormElementText extends FormElement
{
	var			$style = "formText";
	var			$size;
	var			$maxlength;
	var			$isreadonly = false;

	function FormElementText($title, $name, $value, $style, $size, $maxlength, $isreadonly=false)
	{
		$this->FormElement($title, $name, $value);
		if ($style != "") $this->style			= $style;
		$this->size				= $size;
		$this->maxlength		= $maxlength;
		$this->isreadonly		= $isreadonly;
	}

	function getTag ()
	{
		return "<input type=\"text\" name=\"".$this->name."\" value=\"".$this->value."\" class=\"".$this->style."\" size=\"".$this->size."\" maxlength=\"".$this->maxlength."\" ".($this->isreadonly ? " readonly" : "")."><BR>";
	}
}

class FormElementOnlyText extends FormElement
{
	var			$style = "formTextOnly";

	function FormElementOnlyText($title, $value, $style)
	{
		$this->FormElement($title, "", $value);
		if ($style != "") $this->style			= $style;
	}

	function getTag ()
	{
		return "<span class=\"".$this->style."\">".$this->value."</span><br>";
	}
}

class FormElementPassword extends FormElement
{
	var			$style = "formText";
	var			$size;
	var			$maxlength;

	function FormElementPassword ($title, $name, $value, $style, $size, $maxlength)
	{
		$this->FormElement		($title, $name, $value);
		if ($style != "") $this->style			= $style;
		$this->size				= $size;
		$this->maxlength		= $maxlength;
	}

	function getTag ()
	{
		return "<input type=\"password\" name=\"".$this->name."\" value=\"".$this->value."\" class=\"".$this->style."\" size=\"".$this->size."\" maxlength=\"".$this->maxlength."\"><BR>";
	}
}

class FormElementCombo extends FormElement
{
	var			$style = "formCombo";
	var			$size;
	var			$values;
	var			$multiple;

	function FormElementCombo ($title, $name, $value, $style, $size, $values, $multiple)
	{
		$this->FormElement($title, $name, $value);
		if ($style != "") $this->style			= $style;
		$this->size				= $size;
		$this->values			= $values;
		$this->multiple			= $multiple;
	}

	function getTag ()
	{
		$r = "";
		$r .= "<select name=\"".$this->name."\" class=\"".$this->style."\"".( $this->size != 0 ? "size=\"".$this->size."\"" : "").($this->multiple ? " multiple" : "").">";
		while (list ($value, $title) = each ($this->values))
		$r .= "<option value=\"$value\"".($value == $this->value ? " selected" : "").">$title</option>";
		$r .= "</select>";
		return $r;
	}
}

class FormElementRadio extends FormElement
{
	var			$style = "formRadio";
	var			$size;
	var			$values;

	function FormElementRadio ($title, $name, $value, $style, $size, $values)
	{
		$this->FormElement($title, $name, $value);
		if ($style != "") $this->style			= $style;
		$this->size				= $size;
		$this->values			= $values;
	}

	function getTag ()
	{
		$r = "";
		$r .= "<span class=\"".$this->style."\">";
		while (list ($value, $title) = each ($this->values))
		$r .= "<input class=\"".$this->style."\" type=radio name=\"".$this->name."\" value=\"$value\"".($value == $this->value ? " checked" : "").">$title<br>\n";
		return $r;
	}
}

class FormElementCheckbox extends FormElement
{
	var			$style = "formCheckbox";
	var			$size;
	var			$values;

	function FormElementCheckbox ($title, $name, $value, $checked, $style)
	{
		$this->FormElement		($title, $name, $value);
		if ($style != "") $this->style			= $style;
		$this->size				= $size;
		$this->values			= $values;
		$this->checked			= $checked;
	}

	function getTag ()
	{
		$r = "";
		$r .= "<span class=\"".$this->style."\">";
		$r .= "<input type=checkbox name=\"".$this->name."\" value=\"".$this->value."\"".($this->checked ? " checked" : "").">$title<br>\n";
		return $r;
	}
}

class FormElementTextarea extends FormElement
{
	var			$style = "formTextarea";
	var			$cols;
	var			$rows;

	function FormElementTextarea ($title, $name, $value, $style, $cols, $rows)
	{
		$this->FormElement($title, $name, $value);
		if ($style != "") $this->style			= $style;
		$this->cols				= $cols;
		$this->rows				= $rows;
	}

	function getTag ()
	{
		return "<textarea cols=".$this->cols." rows=".$this->rows." name=\"".$this->name."\" class=\"".$this->style."\">".$this->value."</textarea><BR>";
	}
	/*
	<textarea cols="1" rows="2" name="name"></textarea>
	*/
}

class FormElementHidden extends FormElement
{
	function FormElementHidden ($name, $value)
	{
		$this->FormElement("", $name, $value);
	}

	function getTag ()
	{
		return "<input type=\"hidden\" name=\"".$this->name."\" value=\"".$this->value."\">";
	}
}

class Form
{
	var			$title;
	var			$name;
	var			$method;
	var			$action;

	var			$groups;
	var			$elements;

	// Styles
	var			$width					= "80%";

	var			$title_bgcolor			= "#004BAB";
	var			$title_style			= "naGridTitle";

	var			$group_bgcolor			= "#B9D1F0";
	var			$group_style			= "group_style";

	var			$element_bg_style		= array ("naGrid2", "naGrid1");
	var			$element_style			= "element_style";
	var			$element_separator		= ":";

	var			$issubmit				= true;
	var			$submit_bgcolor			= "#B9D1F0";
	var			$submit_title			= "Submit";
	var			$submit_style			= "formbutton";

	var			$isreset				= true;
	var			$reset_title			= "Reset";
	var			$reset_style			= "formreset";

	function Form ($title, $name, $method, $action)
	{
		$this->title	= $title;
		$this->name		= $name;
		$this->method	= $method;
		$this->action	= $action;
		$this->addGroup	(FORM_GROUP_HIDDEN, "");
		$this->addGroup	(FORM_GROUP_MAIN, "");
	}

	function addGroup ($name, $title)
	{
		$this->groups[] = new FormGroup ($name, $title);
	}

	function addElement ($group, $element)
	{
		$this->elements[$group][] = $element;
	}

	function getForm ()
	{
		$r  = "";
		$r .= '<form method="'.$this->method.'" name="'.$this->name.'" action="'.$this->action.'" style="margin: 0px;">';
		$r .= '<table border=0 width='.$this->width.' cellpadding=5 cellspacing=1 class=naBrDr>';
		$r .= '<tr><td colspan=3 bgcolor='.$this->title_bgcolor.' class="'.$this->title_style.'"><span class="'.$this->title_style.'">'.$this->title.'</span></td></tr>';

		for ($group_i=0; $group_i<sizeof ($this->groups); $group_i++)
		{
			$group = $this->groups[$group_i];

			if ($group->name != FORM_GROUP_MAIN && $group->name != FORM_GROUP_HIDDEN)
			{
				$r .= "<tr>\n\t<td colspan=3 bgcolor=".$this->group_bgcolor."><span class=\"".$this->group_style."\">".$group->title."</td>\n</tr>\n";
			}

			$color = 0;
			for ($element_i=0; $element_i<sizeof ($this->elements[$this->groups[$group_i]->name]); $element_i++)
			switch ($group->name)
			{
				case FORM_GROUP_HIDDEN:
					$r .= $this->elements[$this->groups[$group_i]->name][$element_i]->getTag ()."\n";
					break;
				default:
					$element = $this->elements[$this->groups[$group_i]->name][$element_i];
					$bgcolor = $this->element_bg_style[$color];
					if ($color == 0) $color = 1; else $color=0;
					if($element->colspan) {
						$r .= "<tr class=$bgcolor>\n\t<td valign=top colspan=3><div align=center class=\"".$this->element_style."\">\n\t";
						$r .= $element->getTag ()."\n";
						$r .= "</td>\n</tr>\n";
					} else {
						$r .= "<tr class=$bgcolor>\n\t<td valign=top width=40%><div align=right class=\"".$this->element_style."\">".$element->title."</td>\n\t<td width=1 valign=top>".$this->element_separator."</td>\n\t<td>";
						$r .= $element->getTag ()."\n";
						$r .= "</td>\n</tr>\n";
					}
					break;
			}
		}

		if ($this->issubmit || $this->isreset)
		{
			$r .= "<tr class=\"".$this->title_style."\">\n\t<td colspan=3 valign=center><div align=center>";
			if ($this->issubmit)
				$r .= "<input type=submit value=\"".$this->submit_title."\" class=\"".$this->submit_style."\">";
			if ($this->isreset)
				$r.= "&nbsp;<input type=reset value=\"".$this->reset_title."\" class=\"".$this->reset_style."\">";
			$r .= "</td>\n</tr>";
		}
		$r .= "</form>\n";
		$r .= "</table>\n";

		return $r;
	}

}

?>