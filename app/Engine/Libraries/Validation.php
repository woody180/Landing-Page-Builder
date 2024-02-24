<?php namespace App\Engine\Libraries;

/*
 * Avaialble validators
 * alpha
 * alpha_spaces
 * numeric
 * alpha_num
 * alpha_num_spaces
 * valid_email
 * valid_url
 * valid_slug
 * min[]
 * max[]
 * ext[jpg,jpeg,gif,bmp]
 * min_size[1234]
 * max_size[1234]
 * required
 * valid_input
 * string
 * 
 * To make it work
 * $valiate = $validation
            ->with($body)
            ->rules([
                'name|Name' => 'required|alpha',
                'username|UserName' => 'required|min[4]|max[20]|alpha_num',
                'email|eMail' => 'valid_email|min[5]',
                'password|Password' => 'min[5]'
            ])
            ->validate();
 * 
 */


class Validation {
    
    protected $body;
    protected $rules;
    protected $errors;


    public function __construct() {
        $this->errors = [];
    }
    
    
    private function rulesEncode(string $rule) {
        $decodedRule = explode('|', $rule);
        return $decodedRule;
    }
    
    
    private function makeValid(string $param, string $name, $bodyVal, $realName = null) {
        
        $readableName = $realName ? $realName : $name;
        
        if (preg_match('/min\[.*\]/', $param)) {
            preg_match('/min([\[](.*)[\]])/', $param, $match);
            $num = $match[2];
            
            if (!empty($bodyVal)) {
                if (!is_array($bodyVal)) {
                    if (mb_strlen($bodyVal) < $num)
                        $this->errors[$name][] = "$readableName field must contain at least $num characters.";
                } else {
                    foreach ($bodyVal as $val) {
                        if (mb_strlen($val) < $num)
                            $this->errors[$name][] = "$readableName field must contain at least $num characters.";
                    }
                }
            }
            
            
        }
        
        if (preg_match('/max\[.*\]/', $param)) {
            preg_match('/max([\[](.*)[\]])/', $param, $match);
            $num = $match[2];
            
            if (!empty($bodyVal)) {
                if (!is_array($bodyVal)) {
                    if (mb_strlen($bodyVal) > $num)
                        $this->errors[$name][] = "$readableName field maximum characters constraint is - $num.";
                } else {
                    foreach ($bodyVal as $val) {
                        if (mb_strlen($val) > $num)
                            $this->errors[$name][] = "$readableName field maximum characters constraint is - $num.";
                    }
                }
            }
        }
        
        if ($param === 'required') {
            
            if (!empty($bodyVal)) {
                if (!is_array($bodyVal)) {
                    if (strlen($bodyVal) < 1) 
                        $this->errors[$name][] = "$readableName field can't be empty.";
                } else {
                    
                    if (isset($bodyVal['error']) && strlen($bodyVal['name'][0]) < 1) {
                        $this->errors[$name][] = "$readableName field can't be empty.";
                    }
                    
                    if (empty($_FILES)) {    
                        foreach ($bodyVal as $val) {
                            if (strlen($val) < 1) {
                                $this->errors[$name][] = "$readableName field can't be empty.";
                            }
                        }
                    }
                }
            } else {
                $this->errors[$name][] = "$readableName field can't be empty.";
            }
        }


        // Validate file extensions
        if (preg_match('/ext[[](.*)[]]/', $param, $match)) {
            
            $extArray = explode(',', $match[1]);
            
            if (!empty($bodyVal)) {
                
                if (!empty($bodyVal['name'])) {
                    if (!is_array($bodyVal['name'])) {
                        
                        $ext = pathinfo($bodyVal['name'], PATHINFO_EXTENSION);
                        
                        if (!in_array($ext, $extArray)) {
                            $this->errors[$name][] = "Invalid extension name - <b>$ext</b>!";
                        }
                    } else {
                        
                        if (!empty($bodyVal['name'][0])) {
                            foreach ($bodyVal['name'] as $val) {

                                $ext = pathinfo($val, PATHINFO_EXTENSION);

                                if (!in_array($ext, $extArray))
                                    $this->errors[$name][] = "Invalid extension name - <b>$ext</b>!";
                            }
                        }
                    }
                    
                }
            }
        }
        
        
        // Minimum file size
        if (preg_match('/min_size[[](.*)[]]/', $param, $match)) {
            $minSize = $match[1];
            
            if (!empty($bodyVal)) {
                if (!is_array($bodyVal['name'])) {
                    if (!empty($bodyVal['name'])) {
                        if ($bodyVal['size'] < $minSize)
                            $this->errors[$name][] = "File is too small. Minimum size is {$minSize}!";
                    }
                } else {
                    if (!empty($bodyVal['name'][0])) {
                        foreach ($bodyVal['size'] as $size) {
                            if ($size < $minSize) {
                                $this->errors[$name][] = "File is too small. Minimum size is {$minSize}!";
                            }
                        }
                    }
                }
            }
        }
        
        
        // Maximum file size
        if (preg_match('/max_size[[](.*)[]]/', $param, $match)) {
            $maxSize = $match[1];
            
            if (!empty($bodyVal)) {
                if (!is_array($bodyVal['name'])) {
                    if (!empty($bodyVal['name'])) {
                        if ($bodyVal['size'] > $maxSize)
                            $this->errors[$name][] = "File is too large - {$bodyVal['size']}. Maximum size is {$maxSize}!";
                    }
                } else {
                    if (!empty($bodyVal['name'][0])) {
                        foreach ($bodyVal['size'] as $size) {
                            if ($size > $maxSize) {
                                $this->errors[$name][] = "File is too large - {$size}. Maximum size is {$maxSize}!";
                            }
                        }
                    }
                }
            }
        }

        
        
        if ($param === 'valid_email') {
            
            if (!empty($bodyVal)) {
                if (!is_array($bodyVal)) {
                    if (!preg_match('/^[\w\W0-9\-]{3,20}\@[\w\W0-9\-]{2,10}\.[\w\W]{2,4}$/', $bodyVal))
                        $this->errors[$name][] = "Invalid $readableName address!";
                } else {
                    foreach ($bodyVal as $val) {
                        if (!preg_match('/^[\w\W0-9\-]{3,20}\@[\w\W0-9\-]{2,10}\.[\w\W]{2,4}$/', $val))
                            $this->errors[$name][] = "Invalid $readableName address!";
                    }
                }
            }
        }
        
        
        if ($param === 'valid_url') {
            
            if (!empty($bodyVal)) {
                if (!is_array($bodyVal)) {
                    $urlParts = explode('://', $bodyVal);
                    $partOne = $urlParts[0] ?? '';
                    $partTwo = $urlParts[1] ?? '';
                    $validParts = ['http', 'https', 'ftp'];

                    $str = '';
                    if (empty($partTwo)) {
                        $newUrl = $bodyVal;
                        $str = $this->str2url($newUrl);
                    } else {
                        $newUrl = $partTwo;
                        $str = $this->str2url($partTwo);
                    }

                    if (strcmp($newUrl, $str) < 0) {
                        $this->errors[$name][] = 'Url is invalid';
                    } else if (!empty($partTwo) && in_array($partOne, $validParts)) {

                        if (!filter_var($bodyVal, FILTER_VALIDATE_URL)) {
                            $this->errors[$name][] = 'Url is invalid';
                        }
                    } else if (!empty($partTwo) && !in_array($partOne, $validParts)) {
                        $this->errors[$name][] = 'Url is invalid';
                    }
                } else {
                    
                    foreach ($bodyVal as $val) {
                        
                        $urlParts = explode('://', $val);
                        $partOne = $urlParts[0] ?? '';
                        $partTwo = $urlParts[1] ?? '';
                        $validParts = ['http', 'https', 'ftp'];

                        $str = '';
                        if (empty($partTwo)) {
                            $newUrl = $val;
                            $str = $this->str2url($newUrl);
                        } else {
                            $newUrl = $partTwo;
                            $str = $this->str2url($partTwo);
                        }

                        if (strcmp($newUrl, $str) < 0) {
                            $this->errors[$name][] = 'Url is invalid';
                        } else if (!empty($partTwo) && in_array($partOne, $validParts)) {

                            if (!filter_var($val, FILTER_VALIDATE_URL)) {
                                $this->errors[$name][] = 'Url is invalid';
                            }
                        } else if (!empty($partTwo) && !in_array($partOne, $validParts)) {
                            $this->errors[$name][] = 'Url is invalid';
                        }
                    }
                }
            }
        }


        if ($param === 'valid_slug') {

            if (!empty($bodyVal)) {
                if (!is_array($bodyVal)) {
                    $str = $this->str2url($bodyVal);

                    if ($str !== $bodyVal)
                        $this->errors[$readableName][] = "Slug is invalid!";
                } else {
                    foreach ($bodyVal as $val) {
                        
                        $str = $this->str2url($val);
                        
                        if ($str !== $val)
                            $this->errors[$readableName][] = "Slug is invalid!";
                    }
                }
            }
        }
        
        
        if ($param === 'alpha') {
            
            if (!empty($bodyVal)) {
                if (!is_array($bodyVal)) {
                    if ( !preg_match('/^[a-zA-Zა-ჰа-яА-Я()]+$/', $bodyVal))
                        $this->errors[$name][] = "$readableName - Only alphabetical characters are allowed!";
                } else {
                    foreach ($bodyVal as $val) {
                        if ( !preg_match('/^[a-zA-Zა-ჰа-яА-Я()]+$/', $val))
                            $this->errors[$name][] = "$readableName - Only alphabetical characters are allowed!";    
                    }
                }
            }
        }
        
        
        if ($param === 'alpha_num_spaces') {
            
            if (!empty($bodyVal)) {
                if (!is_array($bodyVal)) {
                    if ( !preg_match('/^[\s a-zA-Zა-ჰа-яА-Я1-9()]+$/', $bodyVal))
                        $this->errors[$name][] = "$readableName - Only alphabetical characters are allowed!"; 
                } else {
                    foreach ($bodyVal as $val) {
                        if ( !preg_match('/^[\s a-zA-Zა-ჰа-яА-Я1-9()]+$/', $val))
                            $this->errors[$name][] = "$readableName - Only alphabetical characters are allowed!";
                    }
                }
            }
        }
        
        if ($param === 'alpha_spaces') {
            
            if (!empty($bodyVal)) {
                if (!is_array($bodyVal)) {
                    if ( !preg_match('/^[\s a-zA-Zა-ჰа-яА-Я()]+$/', $bodyVal))
                        $this->errors[$name][] = "$readableName - Only alphabetical characters and spaces are allowed!";
                } else {
                    foreach ($bodyVal as $val) {
                        if ( !preg_match('/^[\s a-zA-Zა-ჰа-яА-Я()]+$/', $val))
                            $this->errors[$name][] = "$readableName - Only alphabetical characters and spaces are allowed!";
                    }
                }
            }
        }

        
        
        if ($param === 'alpha_num') {
            
            if (!empty($bodyVal)) {
                if (!is_array($bodyVal)) {
                    if (!preg_match('/^[a-zA-Zა-ჰа-яА-Я0-9()]+$/', $bodyVal))
                        $this->errors[$name][] = "$readableName - Only alphabetical and numeric characters are allowed!";
                } else {
                    foreach ($bodyVal as $val) {
                        if (!preg_match('/^[a-zA-Zა-ჰа-яА-Я0-9()]+$/', $val))
                            $this->errors[$name][] = "$readableName - Only alphabetical and numeric characters are allowed!";
                    }
                }
            }
        }
        
        
        if ($param === 'alpha_num_spaces') {
            
            if (!empty($bodyVal)) {
                if (!is_array($bodyVal)) {
                    if (!preg_match('/^[\s a-zA-Zა-ჰа-яА-Я0-9()]+$/', $bodyVal))
                        $this->errors[$name][] = "$readableName - Only alphabetical and numeric characters are allowed!";
                } else {
                    foreach ($bodyVal as $val) {
                        if (!preg_match('/^[\s a-zA-Zა-ჰа-яА-Я0-9()]+$/', $val))
                            $this->errors[$name][] = "$readableName - Only alphabetical and numeric characters are allowed!";
                    }
                }
            }
        }


        if ($param === 'string') {
            if (!empty($bodyVal)) {
                
                if (!is_array($bodyVal)) {
                    if (!is_string($bodyVal))
                        $this->errors[$name][] = "Not a string.";
                } else {
                    foreach ($bodyVal as $val) {
                        if (!is_string($val))
                            $this->errors[$name][] = "Not a string.";
                    }
                }
            }
        }
        
        
        if ($param === 'valid_input') {
            
            if (!empty($bodyVal)) {
                
                if (!is_array($bodyVal)) {
                    $str = strip_tags($bodyVal);
            
                    if ($str != $bodyVal)
                        $this->errors[$name][] = "None secure characters added.";
                } else {
                    foreach ($bodyVal as $val) {
                        $str = strip_tags($val);
            
                        if ($str != $val)
                            $this->errors[$name][] = "None secure characters added.";
                    }
                }
            }
            
            
        }
        
        
        if ($param === 'numeric') {
            
            if (!empty($bodyVal)) {
                if (!is_array($bodyVal)) {
                    if (!filter_var($bodyVal, FILTER_VALIDATE_INT)) {
                        $this->errors[$name][] = "$readableName - Only numbers are allowed!";
                    }
                } else {
                    foreach ($bodyVal as $val) {
                        if (!filter_var($val, FILTER_VALIDATE_INT)) {
                            $this->errors[$name][] = "$readableName - Only numbers are allowed!";
                        }
                    }
                }
            }
            
            
        }
        
        
    }
    
    
    
    // Create url from string
    private function str2url($str, $options = array()) {
        // Make sure string is in UTF-8 and strip invalid UTF-8 characters
        $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
        
        $defaults = array(
            'delimiter' => '-',
            'limit' => null,
            'lowercase' => true,
            'replacements' => array(),
            'transliterate' => false,
        );
        
        // Merge options
        $options = array_merge($defaults, $options);
        
        $char_map = array(
            // Latin
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C', 
            'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 
            'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O', 
            'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH', 
            'ß' => 'ss', 
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c', 
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 
            'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o', 
            'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th', 
            'ÿ' => 'y',

            // Latin symbols
            '©' => '(c)',

            // Greek
            'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
            'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
            'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
            'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
            'Ϋ' => 'Y',
            'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
            'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
            'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
            'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
            'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',

            // Turkish
            'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
            'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g', 

            // Russian
            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
            'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
            'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
            'Я' => 'Ya',
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
            'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
            'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
            'я' => 'ya',

            // Ukrainian
            'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
            'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',

            // Czech
            'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U', 
            'Ž' => 'Z', 
            'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
            'ž' => 'z', 

            // Polish
            'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z', 
            'Ż' => 'Z', 
            'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
            'ż' => 'z',

            // Latvian
            'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N', 
            'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
            'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
            'š' => 's', 'ū' => 'u', 'ž' => 'z'
        );
        
        // Make custom replacements
        $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
        
        // Transliterate characters to ASCII
        if ($options['transliterate']) {
            $str = str_replace(array_keys($char_map), $char_map, $str);
        }
        
        // Replace non-alphanumeric characters with our delimiter
        $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
        
        // Remove duplicate delimiters
        $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
        
        // Truncate slug to max. characters
        $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
        
        // Remove delimiter from ends
        $str = trim($str, $options['delimiter']);
        
        return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
    }
    

    
    public function with($param) {
        $this->body = json_decode(json_encode($param), true);
        return $this;
    }
    
    
    public function rules(array $param) {
        $this->rules = $param;
        return $this;
    }
    
    
    public function validate() {
        
        foreach ($this->rules as $name => $rule) {
            $realName = null;
            if (preg_match('/(.*)[|](.*)/', $name, $match)) {
                $name = $match[1];
                $realName = $match[2];
            }
            
            $ruleParts = $this->rulesEncode($rule);
            
            foreach ($ruleParts as $rp) {
                if (!isset($this->body[$name])) $this->body[$name] = null;
                $this->makeValid($rp, $name, $this->body[$name], $realName);
            }
        }
        
        return $this->errors;
    }
}
