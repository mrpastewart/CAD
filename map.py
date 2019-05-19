import asyncio
import websockets
import json
import time


async def run():
    async with websockets.connect(
            'ws://185.251.226.123:30121') as websocket:
        while (True):
            time.sleep(5)
            data = await websocket.recv()
            with open('public/map.json', 'w') as file:
                data = json.loads(data)
                json.dump(data, file)

asyncio.get_event_loop().run_until_complete(run())
