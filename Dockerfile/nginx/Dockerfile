
FROM nginx:stable-alpine

# Copia a config customizada
COPY Dockerfile/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copia apenas os assets públicos do CI4
# O contexto deve ser a raiz do projeto
WORKDIR /var/www/html
COPY public/ ./public/

EXPOSE 80