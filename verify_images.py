import struct

def is_valid_png(filepath):
    try:
        with open(filepath, 'rb') as f:
            signature = f.read(8)
            if signature != b'\x89PNG\r\n\x1a\n':
                return False, "Invalid signature"
            
            # Check IHDR
            chunk_len_data = f.read(4)
            if not chunk_len_data:
                return False, "Missing IHDR length"
            length = struct.unpack('>I', chunk_len_data)[0]
            chunk_type = f.read(4)
            if chunk_type != b'IHDR':
                return False, f"First chunk is {chunk_type}, not IHDR"
            
            return True, "Valid PNG"
    except Exception as e:
        return False, str(e)

files = [
    r'd:\all-project\ecomm-perfumes\screenshots\home.png',
    r'd:\all-project\ecomm-perfumes\screenshots\product.png',
    r'd:\all-project\ecomm-perfumes\screenshots\dashboard.png'
]

for f in files:
    valid, msg = is_valid_png(f)
    print(f"{f}: {msg}")
