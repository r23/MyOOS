# Quick start

This SDK contains three primary components:
- [**Elements**](#elements) - Instant Article Markup renderer
- [**Transformer**](#transformer) - Engine which transforms HTML into **Elements** objects
- [**Client**](#client) - Client to publish Instant Articles

## Elements
`Elements` is the object tree class that represents the structure of an Instant Article. This object tree structure ensures that no invalid Instant Article HTML markup is generated. Here is a simple and complete object tree structure, starting with the `InstantArticle` class that holds the full Instant Article.

```php
$article =
    InstantArticle::create()
        ->withCanonicalUrl('http://foo.com/article.html')
        ->withHeader(
            Header::create()
                ->withTitle('Big Top Title')
                ->withSubTitle('Smaller SubTitle')
                ->withPublishTime(
                    Time::create(Time::PUBLISHED)
                        ->withDatetime(
                            \DateTime::createFromFormat(
                                'j-M-Y G:i:s',
                                '14-Aug-1984 19:30:00'
                            )
                        )
                )
                ->withModifyTime(
                    Time::create(Time::MODIFIED)
                        ->withDatetime(
                            \DateTime::createFromFormat(
                                'j-M-Y G:i:s',
                                '10-Feb-2016 10:00:00'
                            )
                        )
                )
                ->addAuthor(
                    Author::create()
                        ->withName('Author Name')
                        ->withDescription('Author more detailed description')
                )
                ->addAuthor(
                    Author::create()
                        ->withName('Author in FB')
                        ->withDescription('Author user in facebook')
                        ->withURL('http://facebook.com/author')
                )
                ->withKicker('Some kicker of this article')
                ->withCover(
                    Image::create()
                        ->withURL('https://jpeg.org/images/jpegls-home.jpg')
                        ->withCaption(
                            Caption::create()
                                ->appendText('Some caption to the image')
                        )
                )
        )
        // Paragraph1
        ->addChild(
            Paragraph::create()
                ->appendText('Some text to be within a paragraph for testing.')
        )
        // Paragraph2
        ->addChild(
            Paragraph::create()
                ->appendText('Other text to be within a second paragraph for testing.')
        )
        // Slideshow
        ->addChild(
            SlideShow::create()
                ->addImage(
                    Image::create()
                        ->withURL('https://jpeg.org/images/jpegls-home.jpg')
                )
                ->addImage(
                    Image::create()
                        ->withURL('https://jpeg.org/images/jpegls-home2.jpg')
                )
                ->addImage(
                    Image::create()
                        ->withURL('https://jpeg.org/images/jpegls-home3.jpg')
                )
        )
        // Paragraph3
        ->addChild(
            Paragraph::create()
                ->appendText('Some text to be within a paragraph for testing.')
        )
        // Ad
        ->addChild(
            Ad::create()
                ->withSource('http://foo.com')
        )
        // Paragraph4
        ->addChild(
            Paragraph::create()
                ->appendText('Other text to be within a second paragraph for testing.')
        )
        // Analytics
        ->addChild(
            Analytics::create()
                ->withHTML(
                    <h1>Some custom code</h1>
                    <script>alert("test");</script>
                )
        )
        // Footer
        ->withFooter(
            Footer::create()
                ->withCredits('Some plaintext credits.')
        );
```

### Rendering the `InstantArticle` Markup

From above, `$article` now contains a complete `InstantArticle` object — a structured representation of an Instant Article — which can be rendered into valid Instant Article HTML Markup by simply calling its `render()` function:

```php
$article->render('<!doctype html>');
```

#### Rendered output of the `InstantArticle` object from above
```xml
<!doctype html>
<html>
<head>
    <link rel="canonical" href="http://foo.com/article.html"/>
    <meta charset="utf-8"/>
    <meta property="op:markup_version" content="v1.0"/>
    <meta property="fb:use_automatic_ad_placement" content="true"/>
</head>
<body>
    <article>
        <header>
            <figure>
                <img src="https://jpeg.org/images/jpegls-home.jpg"/>
                <figcaption>Some caption to the image</figcaption>
            </figure>
            <h1>Big Top Title</h1>
            <h2>Smaller SubTitle</h2>
            <time class="op-published" datetime="1984-08-14T19:30:00+00:00">August 14th, 7:30pm</time>
            <time class="op-modified" datetime="2016-02-10T10:00:00+00:00">February 10th, 10:00am</time>
            <address>
                <a>Author Name</a>
                Author more detailed description
            </address>
            <address>
                <a href="http://facebook.com/author" rel="facebook">Author in FB</a>
                'Author user in facebook'.
            </address>
            <h3 class="op-kicker">Some kicker of this article</h3>
        </header>
        <p>Some text to be within a paragraph for testing.</p>
        <p>Other text to be within a second paragraph for testing.</p>
        <figure class="op-slideshow">
            <figure>
                <img src="https://jpeg.org/images/jpegls-home.jpg"/>
            </figure>
            <figure>
                <img src="https://jpeg.org/images/jpegls-home2.jpg"/>
            </figure>
            <figure>
                <img src="https://jpeg.org/images/jpegls-home3.jpg"/>
            </figure>
        </figure>
        <p>Some text to be within a paragraph for testing.</p>
        <figure class="op-ad">
            <iframe src="http://foo.com"></iframe>
        </figure>
        <p>Other text to be within a second paragraph for testing.</p>
        <figure class="op-tracker">
            <iframe>
                <h1>Some custom code</h1>
                <script>alert("test");</script>
            </iframe>
        </figure>
        <footer>
            <aside>Some plaintext credits.</aside>
        </footer>
    </article>
</body>
</html>
```

## Transformer
The `Transformer` interprets *any* markup in order to fill in the [`InstantArticle`](https://github.com/facebook/facebook-instant-articles-sdk-php/blob/master/src/Facebook/Instantarticles/Elements/InstantArticle.php) object structure. The transformation process follows a set of pre-defined selector rules which maps the markup of the input to known `InstantArticles` `Elements`. This user-defined configuration makes the Transformer versatile and powerful.

### Transformer Configuration

The power of the **Transformer** lies in the configuration rules it uses to map elements from the input markup to Instant Article markup. Configuration rules are applied ***bottom-up*** so all new or custom rules should be added at the end of the file.

- Each rule in the configuration file should live in the `rules` array
- Each entry should have at least the `class` attribute set
- All classes referred by this configuration file must implement the [`Rule`](https://github.com/facebook/facebook-instant-articles-sdk-php/blob/master/src/Facebook/InstantArticles/Transformer/Rules/Rule.php) class

The transformer pseudo-algorithm is:

```php
$document = loadHTML($input_file);
foreach($document->childNodes as $node) {
    foreach($rules as $rule) {
        if ($rule->matches($context, $node)) {
            // Apply rule...
        }
    }
}
```

This transformer will run through all elements, and for each element checking all rules. The rule to be applied will need to match 2 conditions:

- Matches context
- Matches selector

#### Matching context
Context is the container element that is now in the pipe being processed. This is returned by the method:

```php
public function getContextClass() {
    return InstantArticle::getClassName();
}
```

If the `Rule` will be handling more than one context, it is possible by returning an array of classes:

```php
public function getContextClass() {
    return array(InstantArticle::getClassName(), Header::getClassName());
}
```

#### Matching selector
The **selector** field will be used only by rules that extend [`ConfigurationSelectorRule`](https://github.com/facebook/facebook-instant-articles-sdk-php/blob/master/src/Facebook/InstantArticles/Transformer/Rules/ConfigurationSelectorRule.php).

The selector field will be used as a *CSS selector*; or as an *Xpath selector* if beginning with `/`.

**Example: using a *CSS Selector* to match a rule**
```javascript
{
    "class": "HeaderRule",
    "selector" : "div.header"
}
```

**Example: using an *Xpath Selector* to match a rule**
```javascript
{
    "class": "HeaderRule",
    "selector" : "//div[class=header]"
}
```

### Example

#### Input HTML
The following markup is a sample of what could be used as input to the Transformer:

```html
<html>
    <head>
        <script type="text/javascript" href="http://domain.com/javascript.js" />
    </head>
    <body>
        <div class="header">
            <div class="title">
                <h1>The article title</h1>
                <h2>Sub Title</h2>
                <span class="author">Author name</author>
            </div>
            <div class="hero-image">
                <img src="http://domain.com/image.png" />
                <div class="image-caption">
                  Some amazing moment captured by Photographer
                </div>
            </div>
        </div>
        <p>Lorem <b>ipsum</b> dolor sit amet, consectetur adipiscing elit. Sed eu arcu porta, ultrices massa ut, porttitor diam. Integer id auctor augue.</p>
        <p>Vivamus mattis, sem id consequat dapibus, odio urna fermentum risus, in blandit dolor justo vel ex. Curabitur a neque bibendum, hendrerit sem in, congue lectus.</p>
        <div class="image">
            <img src="http://domain.com/image.png" />
            <div class="image-caption">
              Some amazing moment captured by Photographer
            </div>
        </div>
        <p>Curabitur vulputate odio eu justo <i>venenatis</i>, a pretium orci placerat. Nam sed neque quis eros vestibulum mattis. Donec vitae mi egestas, laoreet massa et, fringilla libero.</p>
    </body>
</html>
```

#### Full rule configuration file for the HTML above

This rule configuration will:

- run bottom-up
- check if matches "class" (context)
- check if matches "selector" (css or xpath)
- Run the rule (calling the callback method `transform()`)

```javascript
{
    "rules" :
        [
            {
                "class": "TextNodeRule"
            },
            {
                "class": "PassThroughRule",
                "selector" : "html"
            },
            {
                "class": "PassThroughRule",
                "selector" : "head"
            },
            {
                "class": "PassThroughRule",
                "selector" : "script"
            },
            {
                "class": "PassThroughRule",
                "selector" : "body"
            },
            {
                "class": "ItalicRule",
                "selector" : "i"
            },
            {
                "class": "BoldRule",
                "selector" : "b"
            },
            {
                "class": "ParagraphRule",
                "selector" : "p"
            },
            {
                "class": "HeaderTitleRule",
                "selector" : "div.title h1"
            },
            {
                "class": "HeaderSubTitleRule",
                "selector" : "div.title h2"
            },
            {
                "class": "HeaderRule",
                "selector" : "div.header"
            },
            {
                "class": "AuthorRule",
                "selector" : "span.author",
                "properties" : {
                    "author.name" : {
                        "type" : "string",
                        "selector" : "span"
                    }
                }
            },
            {
                "class": "CaptionRule",
                "selector" : "div.image-caption"
            },
            {
                "class": "ImageRule",
                "selector" : "div.image",
                "properties" : {
                    "image.url" : {
                        "type" : "string",
                        "selector" : "img",
                        "attribute": "src"
                    },
                    "image.caption" : {
                        "type" : "element",
                        "selector" : "div.image-caption"
                    }
                }
            },
            {
                "class": "HeaderImageRule",
                "selector" : "div.hero-image",
                "properties" : {
                    "image.url" : {
                        "type" : "string",
                        "selector" : "img",
                        "attribute": "src"
                    },
                    "image.caption" : {
                        "type" : "element",
                        "selector" : "div.image-caption"
                    }
                }
            }
        ]
}
```

### Creating Custom Rules
Each custom rule implemented should comply with full contract of the `Rule` abstract class.

```php
class MyCustomRule extends Rule
{
    public function matchesContext($context)
    {}

    public function matchesNode($node)
    {}

    public function apply($transformer, $container, $node)
    {}
}
```

The best option is to use the `ConfigurationSelectorRule` as base class for all custom Rules. This way the selector and more configurations are inherited by default.

### Invoking Transformer

To transform your markup into InstantArticle markup, follow these steps:

- Create an `InstantArticle` instance
- Create a `Transformer` and load it with rules (programmatically or from a file)
- Load/retrieve the HTML content file in the original markup
- Run the Transformer
- Check for errors/warnings

#### Example
```php
// Loads the rules content file
$rules_file_content = file_get_contents("simple-rules.json", true);

// Instantiate Instant article
$instant_article = InstantArticle::create();

// Creates the transformer and loads the rules
$transformer = new Transformer();
$transformer->loadRules($rules_file_content);

// Example loads the html from a file
$html_file = file_get_contents("simple.html", true);

// Ignores errors on HTML parsing
libxml_use_internal_errors(true);
$document = new \DOMDocument();
$document->loadHTML($html_file);
libxml_use_internal_errors(false);

// Invokes transformer
$transformer->transform($instant_article, $document);

// Get errors from transformer
$warnings = $transformer->getWarnings();

// Renders the InstantArticle markup format
$result = $instant_article->render();
```

## Client

The API Client is a lightweight layer on top of the [Facebook SDK for PHP](https://github.com/facebook/facebook-php-sdk-v4) making it easy to push articles to your Facebook Page. Example:

```php
$article = InstantArticle::create();
$transformer->transform($article, $someDocument);

// Instantiate an API client
$client = Client::create(
    'APP_ID'
    'APP_SECRET',
    'ACCESS_TOKEN',
    'PAGE_ID',
    false // development envirorment?
);

// Import the article
try {
    $client->importArticle($article, $take_live);
} catch (Exception $e) {
    echo 'Could not import the article: '.$e->getMessage();
}
```

### `Helper` class

Since publishing Instant Articles is done to an existing Facebook Page, the `Client` also contains a `Helper` class to simplify fetching the access token for Facebook Pages that you're an admin of. Example:

```php
$userAccessToken = 'USER_ACCESS_TOKEN';

// Instantiate a client helper
$helper = Helper::create(
    'APP_ID',
    'APP_SECRET'
);

// Grab pages you are admin of and tokens
$pagesAndTokens = $helper->getPagesAndTokens($userAccessToken)->all();
foreach ($pagesAndTokens as $pageAndToken) {
    echo 'Page ID: ' . $pageAndToken->getField('id');
    echo 'Page name: ' . $pageAndToken->getField('name');
    echo 'Page access token: ' . $pageAndToken->getField('access_token');
}
```
