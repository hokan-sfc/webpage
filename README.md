星空観賞サークル公式サイト
=====

本WebサイトはPHPを用いて実装されているため、直接ソースコードに触れて編集などを行うためにはPHPとオブジェクト指向の基礎的な知識が必須となります。  
以下では開発環境の構築方法等を説明していますが、特に必要がない限りは無理にソースコードを編集しようとはしないほうが懸命でしょう。

### Mac OS X上での開発環境構築方法

#### 1. Xcodeのインストール

Mac App Storeより最新版のXcodeをインストールします。

#### 2. Command Line Tools for Xcodeのインストール

[Appleのデベロッパ向けダウンロードページ](https://developer.apple.com/downloads/index.action)から自分の使用するMac OS XのバージョンとXcodeのバージョンに合ったCommand Line Toolsをダウンロードし、その.dmgファイルを起動してインストールします。  
AppleのデベロッパアカウントはApple IDから無料で作成することができます。

#### 3. Homebrewのインストール

ターミナルを開き、[Homebrewのトップページ](http://brew.sh/index_ja.html)に書かれている以下の様なコマンドを実行してHomebrewをインストールします。  
途中、Macのユーザパスワードの入力などを求められるので、表示される内容をよく読み、指示に従います。
```
$ ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"
```

#### 4. Nginx, PHP, PHPUnit, Xdebugのインストール

Homebrewを使用して必要なソフトウェアをインストールします。  
上手くいかない場合は表示されるエラー内容などをよく読みながら進め、それでもわからない場合はGoogleでエラー文を検索してください。

```
$ brew update
$ brew install nginx php55 phpunit php55-xdebug
```

#### 5. Nginx, PHPの自動起動設定と起動

NginxとPHPを再起動時にも自動起動するよう設定し、起動します。
```
$ launchctl load -w /usr/local/opt/nginx/homebrew.mxcl.nginx.plist
$ launchctl start homebrew.mxcl.nginx
$ launchctl load -w /usr/local/opt/php55/homebrew.mxcl.php55.plist
$ launchctl start homebrew.mxcl.php55
```

なお、NginxやPHPを停止したいときには以下のコマンドを使用します。
```
$ launchctl stop homebrew.mxcl.nginx
$ launchctl stop homebrew.mxcl.php55
```
その他起動状態の確認方法などはlaunchctlのマニュアルを参照してください。

#### 6. レポジトリのクローン

Command Line Toolsをインストールした際にGitがインストールされているはずなので、これを用いて本レポジトリのコピーをPC上に作成します。  
最初に移動するディレクトリ内にwebpageという名前でコピーが作成されます。
```
$ cd /path/to/proper/directory
$ git clone git@github.com:hokan-sfc/webpage.git
```

#### 7. Nginxの設定

作成したコピーをNginxとPHPを用いて表示できるように設定を行います。  
`/usr/local/etc/nginx/nginx.conf`ファイルを編集し、以下のようにしてください。  
なお、`/path/to/local/repogitory/webpage`には6.で作成したディレクトリへのパスを記述します。  
```nginx
worker_processes  1;

events {
    worker_connections  1024;
}

http {
    ##
    # Basic Settings
    ##

    include mime.types;
    default_type application/octet-stream;

    sendfile on;
    tcp_nopush on;
    keepalive_timeout 65;
    server_tokens off;


    ##
    # Logging Settings
    ##

    access_log /usr/local/etc/nginx/logs/access.log;
    error_log /usr/local/etc/nginx/logs/error.log;


    ##
    # Gzip Settings
    ##

    gzip on;
    gzip_disable "msie6";


    ##
    # Server Config
    ##

    server {
        listen 8000;
    
        location / {
            root /path/to/local/repogitory/webpage
            index index.html index.php;
        }
    
        location ~ \.php$ {
            root /path/to/local/repogitory/webpage
            include fastcgi_params;
            fastcgi_pass   127.0.0.1:9000;
            fastcgi_index  index.php;
            fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
        }
    }
}

```
ファイルの編集後、以下のコマンドでNginxを再起動し、ブラウザで`http://localhost:8000`を開いてください。  
Webページが正常に表示されれば開発環境の構築は完了です。ファイルを編集してリロードすると確かに編集が反映されることを確認して下さい。
```
$ launchctl stop homebrew.mxcl.nginx
$ launchctl start homebrew.mxcl.nginx
```
うまく表示されない場合は以下の様な問題がある可能性があります。

+ NginxやPHPが正しく起動されていない
+ 設定ファイルにタイプミスがある
+ PHPのプロセスが9000番以外のポートで起動されている
+ そもそもこのドキュメントに間違いがある

いずれの理由でもGoogleでの検索などを駆使することで解決できると思いますので、表示されているエラーや確認可能なステータスなどをよく読み、もし本ドキュメントに修正が必要であれば随時修正するようにしてください。

#### 8. GitとGithubを用いた開発

本レポジトリで管理されているWebサイトはGitとGithubを用いて開発されるべきです。  
GitやGithubの使い方について解説した資料はWeb上でも書籍としてでも山のようにありますので、これを活用し、ぜひよくバージョン管理された安全な開発を行ってください。


### テストの実行方法

テストには[PHPUnit](https://phpunit.de/index.html)を使用しているので、実行にはこれをインストールする必要があります。  
また、一部のテストではXdebugエクステンションを使用しているので、完全にテストを実行するためにはこれも必要となります。  
これらはMac OS X環境であれば[Homebrew](http://brew.sh/index_ja.html)でインストールできる他、Linuxなど他の環境でも[PHPUnitのダウンロードページ](https://phpunit.de/getting-started.html)および[Xdebugのダウンロードページ](http://www.xdebug.org/download.php)からダウンロード・インストールして利用できます。  
テストは単にプロジェクトのルートディレクトリでインストールあるいはダウンロードしたPHPUnitを引数なしで実行するだけで完了します。

```
$ cd /path/to/repogitory/webpage
$ phpunit
```

また、もしテストのラインカバレッジを知りたい場合には`--coverage-html`オプションを使用することで簡単に可視化することができます。

```
$ cd /path/to/repogitory/webpage
$ mkdir tmp
$ phpunit --coverage-html tmp/
$ open tmp/index.html
```

