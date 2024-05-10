import secrets
import string

def generate_state(length=10):
    alphabet = string.ascii_letters + string.digits
    return ''.join(secrets.choice(alphabet) for _ in range(length))

state = generate_state()
print(state)
