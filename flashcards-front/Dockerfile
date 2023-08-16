FROM node:19-alpine

RUN npm install -y -g npm

WORKDIR /home/node/app

COPY . .
RUN npm install

CMD [ "npm", "run", "build", "--prod" ]
