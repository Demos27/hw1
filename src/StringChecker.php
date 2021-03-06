<?php

namespace Checker;

class StringChecker
{
    private const BRACKET_SYMBOLS = ['(', ')'];

    private const SERVICE_SYMBOLS = ["\\n", "\\t", "\\r", ' '];

    private $allowedSymbols = [];

    private $string;

    private $debugMode;

    /**
     * StringChecker constructor.
     * @param string $string
     * @param int $debugMode
     */
    public function __construct(string $string, int $debugMode = 1)
    {
        $this->string = trim($string);
        $this->allowedSymbols = array_merge(self::BRACKET_SYMBOLS, self::SERVICE_SYMBOLS);
        $this->debugMode = $debugMode;
    }

    /**
     * @return bool
     */
    public function stringCheck(): bool
    {
        if ($this->emptyStringCheck() &&
            $this->notAllowedSymbolsCheck() &&
            $this->bracketCountCheck() &&
            $this->bracketCheck()) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    private function emptyStringCheck(): bool
    {
        if ($this->string === '' || $this->string === null) {
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    private function notAllowedSymbolsCheck(): bool
    {
        $this->string = str_replace(self::SERVICE_SYMBOLS, '', $this->string);
        $tmpString = str_replace(self::BRACKET_SYMBOLS, '', $this->string);
        if ($tmpString !== '') {
            throw new \InvalidArgumentException("\nString: $this->string \ncontain not allowed symbols: $tmpString");
        }
        return true;
    }

    /**
     * @return bool
     */
    private function bracketCountCheck(): bool
    {
        return substr_count($this->string, '(') === substr_count($this->string, ')');
    }

    /**
     * @return bool
     */
    private function bracketCheck(): bool
    {
        $array = str_split($this->string);
        $arraySize = count($array);
        for ($i = 0; $arraySize > 0; $i++) {
            if ($this->debugMode === 1) {
                var_dump('ITERATION:' . $i . ' Array size:' . $arraySize);
                var_dump($array);
            }
            $current = array_key_first($array);
            if ($array[$current] === ')') {
                return false;
            }
            if (in_array($array[$current], self::SERVICE_SYMBOLS)) {
                unset($array[$current]);
                if ($this->debugMode === 1) {
                    var_dump("service: $array[$current]");
                }
                continue;
            }
            $key = array_search(')', $array);
            if ($key) {
                if ($this->debugMode === 1) {
                    var_dump('KEY: ' . $key);
                }
                unset($array[$key]);
                unset($array[$current]);
                $arraySize = $arraySize - 2;
            } else {
                return false;
            }
        }
        if ($this->debugMode === 1) {
            var_dump(['Result Array' => $array]);
        }
        return $array === [];
    }
}