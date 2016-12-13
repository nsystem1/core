<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
    <meta name="theme-color" content="{{ array_get($forum, 'attributes.themePrimaryColor') }}">    
    
    <meta name="HandheldFriendly" content="true" />
    <meta name="image" content="{{ $image }}"/>
    <meta name="keywords" content="{{ $title }}" />    
    <meta name="robots" content="index, follow">
    <meta name="revisit-after" content="1 days">
    <meta name="distribution" content="global" />
    <meta name="audience" content="all" />
    <meta name="rating" content="General"/> 
    <meta name="YahooSeeker" content="INDEX, FOLLOW" />
    <meta name="msnbot" content="INDEX, FOLLOW" />
    <meta name="googlebot" content="INDEX, FOLLOW" />
    
    <meta property="og:locale" content="en_US">
    <meta property="og:title" content="{{ $title }}" />
    <meta property="og:description" content="{{ $description }}"/>
    <meta property="og:url" content="{{ $url }}"/>
    <meta property="og:image" content="{{ $image }}"/>
    <meta property="al:web:url" content="{{ $url }}" /> 
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="mobile-web-app-capable" content="yes">
    
    <meta name="twitter:card" content="player"> 
    <meta name="twitter:title" content="{{ $description }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ $image }}"> 
    <meta name="twitter:domain" content="naijafy.com">
    <meta property="twitter:image:src" content="{{ $image }}">
    <meta property="twitter:url" content="{{ $url }}">      
    
    <!--- RICH SNIPPETS -->
    <link itemprop="url" href="{{ $url }}">
    <meta itemprop="name" content="{{ $title }}">
    <meta itemprop="description" content="{{ $description }}">
    <meta itemprop="image" content="{{ $image }}">
    <meta itemprop="isFamilyFriendly" content="True">
    <link itemprop="thumbnailUrl" href="{{ $image }}">
        
    <link rel="canonical" href="{{ $image }}">
    <link rel="image_src" href="{{ $image }}" />
    <meta name="cover_url" content="{{ $image }}">
    <meta name="cover" content="{{ $image }}">

    @foreach ($cssUrls as $url)
      <link rel="stylesheet" href="{{ $url }}">
    @endforeach

    @if ($faviconUrl = array_get($forum, 'attributes.faviconUrl'))
      <link href="{{ $faviconUrl }}" rel="shortcut icon">
    @endif

    {!! $head !!}
  </head>

  <body>
    {!! $layout !!}

    <div id="modal"></div>
    <div id="alerts"></div>

    @if ($allowJs)
      <script>
        document.getElementById('flarum-loading').style.display = 'block';
      </script>

      @foreach ($jsUrls as $url)
        <script src="{{ $url }}"></script>
      @endforeach

      <script>
        document.getElementById('flarum-loading').style.display = 'none';
        @if (! $debug)
        try {
        @endif
          var app = System.get('flarum/app').default;
          var modules = {!! json_encode($modules) !!};

          for (var i in modules) {
            var module = System.get(modules[i]);
            if (module.default) module.default(app);
          }

          app.boot({!! json_encode($payload) !!});
        @if (! $debug)
        } catch (e) {
          window.location += (window.location.search ? '&' : '?') + 'nojs=1';
          throw e;
        }
        @endif
      </script>
    @else
      <script>
        window.history.replaceState(null, null, window.location.toString().replace(/([&?]nojs=1$|nojs=1&)/, ''));
      </script>
    @endif

    {!! $foot !!}
  </body>
</html>

        @if (! $debug)
        } catch (e) {
          window.location += (window.location.search ? '&' : '?') + 'nojs=1';
          throw e;
        }
        @endif
      </script>
    @else
      <script>
        window.history.replaceState(null, null, window.location.toString().replace(/([&?]nojs=1$|nojs=1&)/, ''));
      </script>
    @endif

    {!! $foot !!}
  </body>
</html>
