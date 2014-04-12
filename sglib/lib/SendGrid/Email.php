<?php

namespace SendGrid;

class Email {

  public $to, 
         $from,
         $from_name,
         $reply_to,
         $cc_list,
         $bcc_list,
         $subject,
         $text,
         $html,
         $headers,
         $smtpapi,
         $attachments;

  public function __construct() {
    $this->from_name        = false;
    $this->reply_to         = false;
    $this->smtpapi          = new \Smtpapi\Header();
  }

  /**
   * _removeFromList
   * Given a list of key/value pairs, removes the associated keys
   * where a value matches the given string ($item)
   * @param Array $list - the list of key/value pairs
   * @param String $item - the value to be removed
   */
  private function _removeFromList(&$list, $item, $key_field = null) {
    foreach ($list as $key => $val) {
      if($key_field) {
        if($val[$key_field] == $item) {
          unset($list[$key]);
        }
      } else {
        if ($val == $item) {
          unset($list[$key]);
        } 
      }
    }
    //repack the indices
    $list = array_values($list);
  }

  public function addTo($email, $name=null) {
    $this->smtpapi->addTo($email, $name);
    return $this;
  }

  public function setTos(array $emails) { 
    $this->smtpapi->setTos($emails);
    return $this;
  }

  public function setFrom($email) {
    $this->from = $email;
    return $this;
  }

  public function getFrom($as_array = false) {
    if($as_array && ($name = $this->getFromName())) {
      return array("$this->from" => $name);
    } else {
      return $this->from;
    }
  }

  public function setFromName($name) {
    $this->from_name = $name;
    return $this;
  }
 
  public function getFromName() {
    return $this->from_name;
  }

  public function setReplyTo($email) {
    $this->reply_to = $email;
    return $this;
  }

  public function getReplyTo() {
    return $this->reply_to;
  }

  public function setCc($email) {
    $this->cc_list = array($email);
    return $this;
  }

  public function setCcs(array $email_list) {
    $this->cc_list = $email_list;
    return $this;
  }

  public function addCc($email) {
    $this->cc_list[] = $email;
    return $this;
  }

  public function removeCc($email) {
    $this->_removeFromList($this->cc_list, $email);

    return $this;
  }

  public function getCcs() {
    return $this->cc_list;
  }

  public function setBcc($email) {
    $this->bcc_list = array($email);
    return $this;
  }

  public function setBccs($email_list) {
    $this->bcc_list = $email_list;
    return $this;
  }
 
  public function addBcc($email) {
    $this->bcc_list[] = $email;
    return $this;
  }

  public function removeBcc($email) {
    $this->_removeFromList($this->bcc_list, $email);
    return $this;
  }

  public function getBccs() {
    return $this->bcc_list;
  }

  public function setSubject($subject) {
    $this->subject = $subject;
    return $this;
  }

  public function getSubject() {
    return $this->subject;
  }

  public function setText($text) {
    $this->text = $text;
    return $this;
  }

  public function getText() {
    return $this->text;
  }

  public function setHtml($html) {
    $this->html = $html;
    return $this;
  }

  public function getHtml() {
    return $this->html;
  }

  public function setAttachments(array $files) {
    $this->attachments = array();

    foreach($files as $filename => $file) {
      if (is_string($filename)) {
        $this->addAttachment($file, $filename);
      } else {
        $this->addAttachment($file);
      }
    }

    return $this;
  }

  public function setAttachment($file, $custom_filename=null) {
    $this->attachments = array($this->_getAttachmentInfo($file, $custom_filename));
    return $this;
  }

  public function addAttachment($file, $custom_filename=null) {
    $this->attachments[] = $this->_getAttachmentInfo($file, $custom_filename);
    return $this;
  }

  public function getAttachments() {
    return $this->attachments;
  }

  public function removeAttachment($file) {
    $this->_removeFromList($this->attachments, $file, "file");
    return $this;
  }

  private function _getAttachmentInfo($file, $custom_filename=null) {
    $info                       = pathinfo($file);
    $info['file']               = $file;
    if (!is_null($custom_filename)) {
      $info['custom_filename']  = $custom_filename;
    }

    return $info;
  }

  public function setCategories($categories) {
    $this->smtpapi->setCategories($categories);
    return $this;
  }

  public function setCategory($category) {
    $this->smtpapi->setCategory($category);
    return $this;
  }

  public function addCategory($category) {
    $this->smtpapi->addCategory($category);
    return $this;
  }

  public function removeCategory($category) {
    $this->smtpapi->removeCategory($category);
    return $this;
  }

  public function setSubstitutions($key_value_pairs) {
    $this->smtpapi->setSubstitutions($key_value_pairs);
    return $this;
  }

  public function addSubstitution($from_value, array $to_values) {
    $this->smtpapi->addSubstitution($from_value, $to_values);
    return $this;
  }

  public function setSections(array $key_value_pairs) {
    $this->smtpapi->setSections($key_value_pairs);
    return $this;
  }
  
  public function addSection($from_value, $to_value) {
    $this->smtpapi->addSection($from_value, $to_value);
    return $this;
  }

  public function setUniqueArgs(array $key_value_pairs) {
    $this->smtpapi->setUniqueArgs($key_value_pairs);
    return $this;
  }

  ## synonym method
  public function setUniqueArguments(array $key_value_pairs) {
    $this->smtpapi->setUniqueArgs($key_value_pairs);
    return $this;
  }

  public function addUniqueArg($key, $value) {
    $this->smtpapi->addUniqueArg($key, $value);
    return $this;
  }
  
  ## synonym method
  public function addUniqueArgument($key, $value) {
    $this->smtpapi->addUniqueArg($key, $value);
    return $this;
  }

  public function setFilters($filter_settings) {
    $this->smtpapi->setFilters($filter_settings);
    return $this;
  }

  ## synonym method
  public function setFilterSettings($filter_settings) {
    $this->smtpapi->setFilters($filter_settings);
    return $this;
  }

  public function addFilter($filter_name, $parameter_name, $parameter_value) {
    $this->smtpapi->addFilter($filter_name, $parameter_name, $parameter_value);
    return $this;
  }
 
  ## synonym method
  public function addFilterSetting($filter_name, $parameter_name, $parameter_value) {
    $this->smtpapi->addFilter($filter_name, $parameter_name, $parameter_value);
    return $this;
  }

  public function getHeaders() {
    return $this->headers;
  }
 
  public function getHeadersJson() {
    if (count($this->getHeaders()) <= 0) {
      return "{}";
    }
    return json_encode($this->getHeaders(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
  }
 
  public function setHeaders($key_value_pairs) {
    $this->headers = $key_value_pairs;
    return $this;
  }
 
  public function addHeader($key, $value) {
    $this->headers[$key] = $value;
    return $this;
  }
 
  public function removeHeader($key) {
    unset($this->headers[$key]);
    return $this;
  }

  public function toWebFormat() {
    $web = array( 
      'to'          => $this->to, 
      'from'        => $this->getFrom(),
      'x-smtpapi'   => $this->smtpapi->jsonString(),
      'subject'     => $this->getSubject(),
      'text'        => $this->getText(),
      'html'        => $this->getHtml(),
      'headers'     => $this->getHeadersJson(),
    );

    if ($this->getCcs())          { $web['cc']          = $this->getCcs(); }
    if ($this->getBccs())         { $web['bcc']         = $this->getBccs(); }
    if ($this->getFromName())     { $web['fromname']    = $this->getFromName(); }
    if ($this->getReplyTo())      { $web['replyto']     = $this->getReplyTo(); }
    if ($this->smtpapi->to && (count($this->smtpapi->to) > 0))  { $web['to'] = ""; }

    $web = $this->updateMissingTo($web);

    if ($this->getAttachments()) {
      foreach($this->getAttachments() as $f) {
        $file             = $f['file'];
        $extension        = null;
        if (array_key_exists('extension', $f)) {
          $extension      = $f['extension'];
        };
        $filename         = $f['filename'];
        $full_filename    = $filename; 

        if (isset($extension)) {
          $full_filename  =  $filename.'.'.$extension;
        }
        if (array_key_exists('custom_filename', $f)) {
          $full_filename  = $f['custom_filename'];
        }

        $contents   = '@' . $file; 
        if (class_exists('CurlFile')) { // php >= 5.5
          $contents = new \CurlFile($file, $extension, $filename);
        }

        $web['files['.$full_filename.']'] = $contents;
      };
    }

    return $web;
  }

  /**
   * There needs to be at least 1 to address, or else the mail won't send.
   * This method modifies the data that will be sent via either Rest 
   */
  public function updateMissingTo($data) {
    if ($this->smtpapi->to && (count($this->smtpapi->to) > 0)) {
      $data['to'] = $this->getFrom();
    } 
    return $data;
  }
}
