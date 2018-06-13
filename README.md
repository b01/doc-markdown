# Disclaimer
WARNING: This developer cares nothing to maintain the illusion of safety and security. The master branch contains experimental
features; I would also encourage you to use the dev-master branch in production to get you used to the fact that there is no
such thing as safe, secure, etc. Have some damn faith and quite being pathetic.

# Description
A tool to generate API documentation from inline Doc-blocks; Using the PHP token_get_all() function to read and parse 
PHP files for code documentation and transforming Doc-blocks into Markdown and storing them in markdown files.

The resulting documentation will match the source code structure layout.

## Background
Inspired by ReadTheDocks, this purpose of this library is to convert PHP Doc-Block comments to Markdown. Which I feel is
a nice option over reStructuredText. As an added bonus, I plan to integrate the use of template engines, such as Sigma or
Twig to allow altering how the Markdown is produced. This should allow producing various document styles without heavy
backend code modification, assuming the API is comprehensible and flexible enough.

### Why
PHP does not have the greatest tools to retrieve doc-blocks, let alone convert them to something like Markdown. While
there are other ways, such as using PHPDocumentor as a middle man; I hope to accomplish this by relying only on the PHP 
core. So I am attempting to use the token_get_all() function exclusively, which presents a challenge itself. I've had 
some success so far, and am hopeful that it will continue, and turn out to be the best decision in the long run.
The ultimate goal is to make converting doc-blocks to markdown less involved.
 
This tool also makes use of the built-in PHP function glob() to find all PHP files in a directories. However, this 
function is not available on all system. But I think Linux/Mac/Windows systems are O.K.
 
### Challenges

#### Parsing PHP Files for Declarations of Various Kinds
Using token_get_all() function to find tokens in PHP is nice, but you have to convert the array that it returns into 
something useful for your program. Which is where it becomes very challenging. Having an array of all kinds of 
tokens that include abstract, class, interface, function, doc comments, and no way to distinguish where one ends and 
anther begins is quite cumbersome, without the proper knowing the original reason for that functions existence, has left
doubt that it should be used for this purpose. 
 
It would be much simpler if there were a way to get all the definitions of any type in a PHP file, as a whole, along 
with their doc-block; like with PHP Reflection API. I initially thought to use the Reflection API, however it does not
filenames. Also there seems to be no way to use the Reflection API to query what was loaded from a class. Rather
you have to know what is defined in the file beforehand. So unfortunately the Reflection API did not suit my needs.
The best I've come across so far is the token_get_all() function. Even the PHP libraries out there that
claim to do this did not meet my needs.

## Usage

```sh
./vendor/bin/docmarkdown <source-dir> <output-dir>
```

## Run Unit Tests

```sh
git clone http://git/KShabazz/doc-markdown.git
cd doc-markdown
composer.phar update
./vendor/bin/phpuni
```