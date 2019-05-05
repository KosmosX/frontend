# Front Manager package
![](https://img.shields.io/badge/version-1.0.1-green.svg)
![](https://img.shields.io/badge/PHP->=7.1.3-blue.svg)
![](https://img.shields.io/badge/Laravel->=5.6-blue.svg)
![](https://img.shields.io/badge/Lumen->=5.6-blue.svg)

*Frontend manager for: Js, CSS, Metatag, Open Graph. You can add and load resources with simple function.*

## Installation

Install with console command

    composer require kosmosx/frontend
    
Or add in composore.json one of:

    "kosmosx/frontend": "~1" // version >=1.0.0 <2.0.0
    
    "kosmosx/frontend": "1.0.*" // version >=1.0.0 <1.1.0

**Support** (If you use a smaller version, compatibility is not guaranteed)
    
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

    $resources = (new FrontendFactory())->scripts();
    or
    $resources = new ScriptsFrontend();
    
    $resources->add('https://code.jquery.com/jquery-3.3.1.min.js', array(),'head.jquery')
        ->add('https://example.com', array("type"=>"script"),'footer');

    
> **'head.jquery'**: 
            **head** it is the context where the resource should be loaded inside the DOM (if omitted, body is used by default);
            **jquery** is the name of resource (not reuqired)
Render tags

    $resources->dump() //render all script tags
    
    //output: 
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://example.com" type="script"></script>

**All method of FrontendInvoker**

    $resources->add(...)        //push in resources new element
              ->dump(...)       //render resources element
              ->has(...)        //find element in resources
              ->forget(...)     //forget element in resources
              ->toArray(...)    

**Function of services(Resource and Metatag)**

    ->exist($resource, $context, $name) //$resource (script, js, og, meta etc..)
                                      //$context (body,footer etc..) if metatag service only 'head'
                                      //$name (name of resources) if metatag service name of type
    
    ->delete($resource, $context, $name)
    ->toArray()
    ->toString()
    ->getContext()
    ->setContext()                    //add extra context to default
    ->cleanText()                     //clean string (remove tag and special charter)

**Example**

    $openGraph = new OpenGraphFrontend();                       //create with Service
    $openGraph = new FrontendInvoker(new OpenGraphFrontend());  //create with invoker that use command
    $openGraph = (new FrontendFactory())->opengraph();          //create with Factory that use invoker

    $openGraph->add('title', 'Resources Manager')->add('description', 'Og description');

    return $openGraph->dump();
    //output:
        <meta property="og:title" content="Resources Manager"></meta>
        <meta property="og:description" content="Og description"></meta>
    
    return $openGraph->dump('og:title');
    //output:
            <meta property="og:title" content="Resources Manager"></meta>

### Support

The pull requests will be reviewed (document the code or write a detailed comment) and if successful they will be accepted

Write to developer@fabriziocafolla.com or create an issue. 
