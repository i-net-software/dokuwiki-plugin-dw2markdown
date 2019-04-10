This folder is for bits of code to help in the rendering of the markdown extra format plus extensions.
MarkdownUltra_Parser.php implements a sub-class of MarkdownExtra_Parser, implemented in
PHP Markdown Extra. It handles extended syntax, and alters the rendering for certain features.

Specific features handled at present:

* Code blocks using ~~~ notation can be extended with a language specifier {lang}, which
  generates markup that can be interpreted by syntaxhighlighter 2.1 (in javascript). (It
  doesn't include the libraries or css, just adds to the markup. The container needs
  to add the libraries and css as required.)
