<!DOCTYPE html>
<head>
    <title>Pusher Test</title>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.6.0/dist/echo.iife.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.7/axios.min.js"></script>
    <script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;
        window.Pusher = Pusher;
        var accessToken = '12|ukRfMVwyVlhnwya0w0vYHAU6iWAsmjUIEJnzOkrb371e6f53';

        axios.defaults.baseURL = 'http://api.print.test';
        axios.interceptors.request.use(function (config) {
            config.headers.Authorization = `Bearer ${accessToken}`;
            return config;
        }, error => Promise.reject(error));

        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: 'ec7ce04a4c4ac933d6df',
            cluster: 'us2',
            forceTLS: true,
            authorizer: (channel, options) => {
                return {
                    authorize: (socketId, callback) => {
                        const data = {
                            socket_id: socketId,
                            channel_name: channel.name
                        };
                        axios.post('/broadcasting/auth', data)
                            .then(response => {
                                callback(false, response.data);
                            })
                            .catch(error => {
                                callback(true, error);
                            });
                    }
                };
            },
        });

        Echo.private(`App.Models.User.1`)
            .listen('MyEvent', (e) => {
                console.log(e);
            });
    </script>
</head>
<body>
<h1>Pusher Test</h1>
<p>
    Try publishing an event to channel <code>my-channel</code>
    with event name <code>my-event</code>.
</p>
</body>
