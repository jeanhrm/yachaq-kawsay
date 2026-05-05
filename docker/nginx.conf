events {}
http {
    include /etc/nginx/mime.types;
    default_type application/octet-stream;
    sendfile on;

    server {
        listen 8080;
        root /var/www/public;
        index index.php index.html;

        add_header X-Frame-Options "SAMEORIGIN";
        add_header X-Content-Type-Options "nosniff";

        charset utf-8;

        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }

        location = /favicon.ico { access_log off; log_not_found off; }
        location = /robots.txt  { access_log off; log_not_found off; }

        location ~ \.php$ {
            fastcgi_pass 127.0.0.1:9000;
            fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
            include /etc/nginx/fastcgi_params;
            fastcgi_param DOCUMENT_ROOT $realpath_root;
        }

        location ~ /\.(?!well-known).* {
            deny all;
        }
    }
}