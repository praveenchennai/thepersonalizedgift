<?php

/**
 * @author Dick Munroe <munroe@csworks.com>
 * @copyright copyright @ by Dick Munroe, 2004-2006
 * @license http://www.csworks.com/publications/ModifiedNetBSD.html
 * @package StructuredDataDumper
 * @version 1.1.0
 */

//
// Edit History:
//
//  Dick Munroe munroe@cworks.com 23-Dec-2004
//	Initial version created.
//
//  Dick Munroe munroe@csworks.com 05-Apr-2006
//      Bullet proof log file functions.
//

include_once('SDD/class.SDD.php') ;

class logfile extends SDD
{

  /**
   * The open file handle.
   *
   * @access private
   */

  var $m_handle ;

  /**
   * Constructor
   *
   * @access public
   * @param string $theFileName The name of the log file.
   */

  function logfile($theFileName)
    {
      if (file_exists($theFileName))
	{
	  $this->m_handle = @fopen($theFileName, 'a') ;
	}
      else
	{
	  $this->m_handle = @fopen($theFileName, 'w') ;
	}

      if ($this->m_handle === FALSE)
	{
	  trigger_error('Failed to open log file: ' . $theFileName . '\n' .
			'Current working directory: ' . getcwd(), E_USER_WARNING) ;
	}
      else
	{
	  $this->SDD(null, true) ;
	}
    }

  /**
   * Close the log file.
   *
   * @access public
   */

  function close()
    {
      if ($this->m_handle !== FALSE)
	{
	  fclose($this->m_handle) ;
	}
    }

  /**
   * Write to a log file.
   *
   * @access public
   * @param mixed Data to be logged.
   * @return integer number of bytes written to the log.
   */

  function writeLog(&$theData)
    {
      if ($this->m_handle !== FALSE)
	{
	  return fwrite($this->m_handle, $theData) ;
	}
      else
	{
	  return 0 ;
	}
    }
}
?>