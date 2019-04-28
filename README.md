# Front Manager package
![](https://img.shields.io/badge/version-1.0.0-green.svg)
![](https://img.shields.io/badge/PHP->=7.1.3-blue.svg)
![](https://img.shields.io/badge/Laravel->=5.6-red.svg)
![](https://img.shields.io/badge/Lumen->=5.6-red.svg)

*Frontend manager for: Js, CSS, Metatag, Open Graph. You can add and load resources with simple function.*

## Installation

Install with console command

    composer require kosmosx/frontend
    
Or add in composore.json one of:

    "kosmosx/frontend": "~1" // version >=1.0.0 <2.0.0
    
    "kosmosx/frontend": "1.0.*" // version >=1.0.0 <1.1.0

**Support** (If you use a smaller version, compatibility is not guaranteed)

PHP >=7.1.3

Laravel / Lumen framework >=5.6 
    
Add provider in config file app.php (if Laravel)

    'providers' => array(
        ...
        'Kosmosx\Frontend\Providers\Kosmosx\FrontendServiceProvider',
    ),

Register provider in bootstrap file (if Lumen)

    
 	$app->register(Kosmosx\Frontend\Providers\FrontManagerServiceProvider);
 	
 	$app->withFacades(); //uncomment if you want to use the Facades of the package
 	
## Let's go

**Basic usage**

    $resources = (new ManagerFactory())->resources();
    or
    $resources = new ResourcesService();
    
    $resources->script('https://code.jquery.com/jquery-3.3.1.min.js', 'head.jquery')
              ->script('https://example.com','footer', array("type"=>"script")
              ->style('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css', 'head.bootstrap');
    
> **'head.jquery'**: 
            **head** it is the context where the resource should be loaded inside the DOM (if omitted, body is used by default);
            **jquery** is the name of resource (not reuqired)
Render tags

    $resources->dump('script') //render all script tags
    
    //output: 
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://example.com" type="script"></script>
    
    
    $resources->dump('script', 'head.jquery') //render only tags named 'jquery' in context 'head'
    
    //output:  
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        
    
    $resources->dump('style') //render all style tags

**All method of ResourcesService**

    $resources->script(...)     //push in resources javascript tags
              ->style(...)      //push in resources style tags
              ->css(...)        //push in resources css code
              ->js(...)         //push in reources javascript code
              ->variable(...)   //push in resources javascript varibles
    
    $resources->dump('script')      //render script tags
    $resources->dump('style')       //render style tags
    $resources->dump('css')         //render css code tags
    $resources->dump('js')          //render javascript code tags
    $resources->dump('variable')    //render javascript varible tags
    
**All method of ResourcesService**

    $metatag = new MetatagService();
    $metatag->meta(...)       //push metatag 
            ->og(...)         //push opengraph tags
            ->extra(...)      //push metatag of DOM (charset, viewport, etc..)
            ->twitter(...)    //push twitter opengraph 
    
    $resources->dump('meta')
    $resources->dump('og')
    $resources->dump('extra')
    $resources->dump('all')

**Other function (Resource and Metatag)**

    ->has($resource, $context, $name) //$resource (script, js, og, meta etc..)
                                      //$context (body,footer etc..) if metatag service only 'head'
                                      //$name (name of resources) if metatag service name of type
    
    ->forget($resource, $context, $name)
    ->toArray()
    ->toString()
    ->getContext()
    ->setContext()                    //add extra context to default
    ->cleanText()                     //clean string (remove tag and special charter)

**Example**

     $metatag->og('title', 'Resources Manager')
             ->og('description', 'Og description')
             ->twitter('title', 'Resources Manager')
             ->twitter('description', 'Twitter description');
    
    return $metatag->dump('og');
    //output:
        <meta property="og:title" content="Resources Manager"></meta>
        <meta property="og:description" content="Og description"></meta>
        <meta property="twitter:title" content="Resources Manager"></meta>
        <meta property="twitter:description" content="Twitter description"></meta>
    
    return $metatag->dump('og', 'og:title');
    //output:
            <meta property="og:title" content="Resources Manager"></meta>

### Support

The pull requests will be reviewed (document the code or write a detailed comment) and if successful they will be accepted

Write to developer@fabriziocafolla.com or create an issue. 
