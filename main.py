from fastapi import FastAPI
import sqlite3

app = FastAPI()

def init_db():
    conn = sqlite3.connect(":memory:")
    conn.execute("CREATE TABLE users (id INTEGER, name TEXT, password TEXT)")
    conn.execute("INSERT INTO users VALUES (1, 'admin', 'secret123')")
    conn.commit()
    return conn

db = init_db()

@app.get("/")
def root():
    return {"message": "Hola, esta es mi app de práctica DAST"}

@app.get("/health")
def health():
    return {"status": "ok"}

@app.get("/items/{item_id}")
def get_item(item_id: int):
    return {"item_id": item_id, "name": f"Item {item_id}"}

# ⚠️ VULNERABILIDAD INTENCIONAL - SQL Injection
@app.get("/users")
def get_user(name: str):
    query = f"SELECT * FROM users WHERE name = '{name}'"
    cursor = db.execute(query)
    rows = cursor.fetchall()
    return {"users": rows, "query_used": query}

# ⚠️ VULNERABILIDAD INTENCIONAL - Expone datos sensibles
@app.get("/debug")
def debug_info():
    return {
        "database": "sqlite",
        "server": "uvicorn",
        "secret_key": "super-secret-key-12345",
        "admin_password": "admin123",
        "version": "1.0.0"
    }

# ⚠️ VULNERABILIDAD INTENCIONAL - Sin autenticación
@app.delete("/users/{user_id}")
def delete_user(user_id: int):
    db.execute(f"DELETE FROM users WHERE id = {user_id}")
    return {"message": f"User {user_id} deleted"}
