FROM python:3.8.12-alpine3.15 as builder

RUN apk update && apk add --no-cache \ 
    libpq-dev \
    gcc \
    musl-dev \
    libev-dev \
    libffi-dev \
    openssl-dev \
    python3-dev \
    cargo
WORKDIR /build-app
COPY requirements.txt .
RUN pip install wheel && pip wheel --wheel-dir=/build-app/wheels -r requirements.txt

FROM python:3.8.12-alpine3.15 AS runner
RUN apk update --no-cache && apk add --no-cache libpq libev
WORKDIR /app
COPY . .
COPY --from=builder /build-app/wheels /app/wheels
RUN pip install --no-index --find-links=/app/wheels -r requirements.txt
CMD ["python", "app.py"]