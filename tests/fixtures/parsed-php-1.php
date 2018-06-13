<?php $parsePhpFiles = array (
  0 => 
  array (
    'file_comment' => NULL,
    'namespace' => 'namespace QL\\DocMarkdown\\Tests\\Dummies',
    'abstracts' => 
    array (
      0 => 
      array (
        'statement' => 'abstract class AbstractDummyA',
        'doc_comment' => '/**
 * Class AbstractDummyA
 *
 * @package \\QL\\DocMarkdown\\Tests\\Dummies
 */',
        'methods' => 
        array (
          0 => 
          array (
            'statement' => 'abstract protected function method1()',
            'doc_comment' => NULL,
          ),
        ),
      ),
    ),
    'file' => '/Users/kshabazz/Projects/doc-markdown/tests/Dummies/AbstractDummyA.php',
    'file_type' => 'abstract-class',
  ),
  1 => 
  array (
    'file_comment' => '/**
 * File documentation goes here.
 */',
    'namespace' => 'namespace QL\\DocMarkdown\\Tests\\Dummies',
    'abstracts' => 
    array (
      0 => 
      array (
        'statement' => 'abstract class AbstractDummyB',
        'doc_comment' => '/**
 * Class AbstractDummyA
 *
 * @package QL\\DocMarkdown\\Tests\\Dummies
 */',
        'methods' => 
        array (
          0 => 
          array (
            'statement' => 'abstract protected function method1()',
            'doc_comment' => '/**
     * Documentation for method on of AbstractDummyB
     *
     * @return mixed
     */',
          ),
        ),
      ),
    ),
    'file' => '/Users/kshabazz/Projects/doc-markdown/tests/Dummies/AbstractDummyB.php',
    'file_type' => 'abstract-class',
  ),
  2 => 
  array (
    'file_comment' => NULL,
    'namespace' => 'namespace Kshabazz\\DocMarkdown\\Tests\\Dummies',
    'constants' => 
    array (
      0 => 
      array (
        'statement' => 'const TEST_1 = 1',
        'doc_comment' => '/** int Constant documentation. */',
      ),
    ),
    'functions' => 
    array (
      0 => 
      array (
        'statement' => 'function dumFun()',
        'doc_comment' => '/**
 * Return the string "dum fun".
 *
 * @return string
 */',
      ),
    ),
    'file' => '/Users/kshabazz/Projects/doc-markdown/tests/Dummies/AllKinds.php',
    'file_type' => 'file',
  ),
  3 => 
  array (
    'file_comment' => NULL,
    'namespace' => 'namespace QL\\DocMarkdown\\Tests\\Dummies',
    'classes' => 
    array (
      0 => 
      array (
        'statement' => 'class ClassDummyA',
        'doc_comment' => '/**
 * Class ClassDummyA
 *
 * @package \\QL\\DocMarkdown\\Tests\\Dummies
 */',
        'constants' => 
        array (
          0 => 
          array (
            'statement' => 'const TEST_1 = \'1234\'',
            'doc_comment' => '/** A string constant. */',
          ),
        ),
        'properties' => 
        array (
          0 => 
          array (
            'statement' => 'private $property1',
            'doc_comment' => '/** @var int Field of ClassDummyA */',
          ),
        ),
        'methods' => 
        array (
          0 => 
          array (
            'statement' => 'public function __construct()',
            'doc_comment' => '/**
     * Constructor
     */',
          ),
        ),
      ),
    ),
    'functions' => 
    array (
      0 => 
      array (
        'statement' => 'public function getProperty1()',
        'doc_comment' => '/**
     * Getter for property1.
     *
     * @return int
     */',
      ),
    ),
    'file' => '/Users/kshabazz/Projects/doc-markdown/tests/Dummies/ClassDummyA.php',
    'file_type' => 'class',
  ),
  4 => 
  array (
    'file_comment' => NULL,
    'namespace' => 'namespace QL\\DocMarkdown\\Tests\\Dummies',
    'interfaces' => 
    array (
      0 => 
      array (
        'statement' => 'interface InterfaceDummyA',
        'doc_comment' => '/**
 * Interface InterfaceDummyA For testing DocMarkdown with an interface class.
 *
 * @package QL\\DocMarkdown\\Tests\\Dummies
 */',
        'constants' => 
        array (
          0 => 
          array (
            'statement' => 'const TEST_1 = \'1234\'',
            'doc_comment' => '/** Test constant */',
          ),
        ),
        'methods' => 
        array (
          0 => 
          array (
            'statement' => 'public function __construct()',
            'doc_comment' => '/**
     * A constructor with no parameters.
     */',
          ),
        ),
      ),
    ),
    'file' => '/Users/kshabazz/Projects/doc-markdown/tests/Dummies/InterfaceDummyA.php',
    'file_type' => 'interface',
  ),
);