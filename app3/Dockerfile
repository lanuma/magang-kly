FROM node:16-alpine

ENV APP_DIR /app/
WORKDIR ${APP_DIR}

COPY . ./
RUN npm install
RUN npm run build

ENV HOST 0.0.0.0

EXPOSE 3000

CMD ["npm", "run", "dev"]