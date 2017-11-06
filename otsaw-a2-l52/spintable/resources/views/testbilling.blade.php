<!DOCTYPE html>
<html>
    <head>

        <!-- Metadata -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">


        <!-- CSS -->



        <!-- JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.25/vue.min.js" language="javascript"></script>
        <script src="https://cdn.socket.io/socket.io-1.4.5.js" language="javascript"></script>
        <title>Quabii</title>


    </head>
    <body>

       <form action="/your-server-side-code" method="POST">
          <script
            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
            data-key="pk_test_S1eOsTBzunXevO6jliKIatk3"
            data-amount="999"
            data-name="Demo Site"
            data-description="Widget"
            data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
            data-locale="auto"
            data-currency="sgd">
          </script>
        </form>

        <script>
        </script>
    </body>
</html>
