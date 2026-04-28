# ==========================================
# Stage 1: Builder (Compilação e Composer)
# ==========================================
FROM php:8.3-fpm-alpine AS builder

# Instala as dependências de sistema necessárias para compilar as extensões
# icu-dev é para o intl; oniguruma-dev é para o mbstring
RUN apk add --no-cache \
    icu-dev \
    oniguruma-dev \
    git \
    unzip

# Compila e instala as extensões do PHP
RUN docker-php-ext-install intl mbstring pdo pdo_mysql

# Baixa o Composer oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho
WORKDIR /var/www/html

# 1. Copia apenas os arquivos do Composer primeiro
# Isso otimiza o cache do Docker: se você mudar seu código mas não as dependências, 
# o Docker não vai baixar tudo do zero novamente.
COPY composer.json composer.lock ./

# 2. Roda o composer install (sem o autoloader ainda)
RUN composer install --no-scripts --no-autoloader

# 3. Copia o restante do código do CodeIgniter
COPY . .

# 4. Gera o autoloader otimizado (excelente para performance)
RUN composer dump-autoload --optimize

# ==========================================
# Stage 2: Imagem Final (Enxuta)
# ==========================================
FROM php:8.3-fpm-alpine

# Instala *apenas* as bibliotecas de execução (runtime) 
# Sem isso, as extensões compiladas no Stage 1 não funcionam na imagem final
RUN apk add --no-cache \
    icu-libs \
    oniguruma

# Copia as extensões compiladas e as configurações do builder
COPY --from=builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/

WORKDIR /var/www/html

# Copia todo o projeto (incluindo a pasta vendor gerada) do builder para a imagem final
# Já alterando as permissões para o usuário padrão do fpm (www-data)
COPY --from=builder --chown=www-data:www-data /var/www/html .

# Expõe a porta do PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]