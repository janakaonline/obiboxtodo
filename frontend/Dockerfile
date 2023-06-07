FROM node:lts-alpine

ARG DEBIAN_FRONTEND=noninteractive

RUN npm install -g http-server

WORKDIR /usr/src/app

COPY frontend/package*.json ./

RUN npm install

COPY frontend/. .

RUN npm run build

EXPOSE 8080

CMD [ "http-server", "dist" ]
