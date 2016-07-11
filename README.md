Brain Strategist : Helpdesk management system with symfony 3
========
<h2>Relax, take a coffee cup and follow the guide</h2>
        <div class="blog-post">
            <h2 class="blog-post-title">Requirements</h2>
            <ul>
                <li>A server based on Linux with Apache/Nginx </li>
                <li>A database server using MySQL</li>
                <li>A PHP version upper or equal to 5.5.9 </li>
                <li>Composer must be installed
                   <pre style="margin-top: 20px">
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer.phar
alias composer='/usr/local/bin/composer.phar'</pre>
                 </li>
            </ul>
            <h3>Optionnal</h3>
            If you want to modify your installation, we advise you to install NPM and Rubygems. This software use friendly addons to manage your assets like Grunt, Saas and Bower. Follow the documentation on their dedicated websites for more informations.
        </div>
        <div class="blog-post">
            <h2 class="blog-post-title">Setup</h2>
            <ol>
                 <li> Configure your workspace directory to match <b>the web folder</b> as target document root directory. More informations about your web server host setup are available <a href="http://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html" target="_blank">HERE</a></li>
                 <li> Checkout the latest version on <a href="https://github.com/sebbrunet334/brainstrategist" target="_blank">Github</a></li>
                 <li> <b>Clone the repository</b>  on your webserver using ssh&nbsp;<code>git clone git@github.com:sebbrunet334/brainstrategist.git</code></li>
                 <li> <b>Update your project</b> using composer <code>composer update</code></li>
                 <li> <b>Create your configuration file</b> parameters.yml under the app/config directory based on the configuration listed below. Modify this sample configuration feet to your needs.
                  <pre style="margin-top: 20px"># This file is auto-generated during the composer install
parameters:
    database_host: 127.0.0.1
    database_port: null
    database_name: db_name
    database_user: db_user
    database_password: db_password
    mailer_transport: smtp
    mailer_host: 127.0.0.1
    mailer_user: null
    mailer_password: null
    secret: yourSecretTokenId
    oauth_facebook_id : xxxxxxxxx
    oauth_facebook_secret : xxxxxxxxx
    </pre>
                 </li>
                 <li> <b>Install the database schema</b> <code>php bin/console doctrine:schema:update --force</code></li>
                 <li> <b>Dump your assets on production</b> <code>php bin/console assetic:dump</code></li>
                 <li> <b>Compile your assets</b> executing the command<code>Grunt</code> (require Grunt installed by the following NPM command <code>npm install -g grunt-cli</code>)</li>
                 <li> <b>Clear your cache</b> <code>sudo -H -u  www-data  php bin/console cache:clear</code> (or just delete it <b>carrefully</b> <code>rm -rf var/cache/*</code>)</li>
                <li> <b>Enjoy :)</b></li>
            </ol>
          </div>
          <div class="blog-post">
            <h2 class="blog-post-title">Questions & Support</h2>
            <p>
            If you encountered some troubleshooting during the installation, feel free to <a href="mailto:brunetsebastien33@gmail.com"> leave me a message</a> or open an issue on the Github project page.
             Don't forget that you are on a free project, with a free developer behind. I will answer you asap :)
            strong>Remember</strong> This software is free to use and deliver without any guarantee. You are the own responsible for the use of this software. Please, be aware that neither backup system will be deliver with this software, put one in place if you are using this system in production. 
            </p>
          </div>
          <div class="blog-post">
            <h2 class="blog-post-title">Liscence</h2>
            <p>
                    <b>Brain Strategist : A sowftware providing a free and strong Helpdesk management system.</b>
                   Copyright (C) 2016  Brunet Sebastien
                    <br/>
                    This program is free software: you can redistribute it and/or modify
                    it under the terms of the GNU General Public License as published by
                    the Free Software Foundation, either version 3 of the License, or any later version.
                     <br/>
                    This program is distributed in the hope that it will be useful,
                    but WITHOUT ANY WARRANTY; without even the implied warranty of
                    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
                    GNU General Public License for more details.
                     <br/>
                    You should have received a copy of the GNU General Public License
                    along with this program.  If not, see <a href="http://brainstrategist.fr/en/liscence"> the complete license here</a>
            </p>
          </div>
