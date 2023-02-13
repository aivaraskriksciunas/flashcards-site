FROM node:19-alpine

RUN npm install -y -g npm

WORKDIR /home/node/app

EXPOSE 8000

CMD [ "npm", "run", "dev", "--", "--port", "8000", "--host", "0.0.0.0" ]