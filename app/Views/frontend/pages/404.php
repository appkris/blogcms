<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>404 - Page Not Found</title>
  <style>
    body {
      margin: 0;
      background: #111;
      color: #fff;
      font-family: 'Courier New', monospace;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    header, footer {
      text-align: center;
      padding: 1em;
    }

    h1 {
      font-size: 3rem;
      color: #0f0;
      margin: 0.5em 0 0.2em;
    }

    p {
      font-size: 1.2rem;
      color: #ccc;
      margin: 0 0 1em;
    }

    canvas {
      border: 2px solid #0f0;
      background-color: #000;
      margin-bottom: 2em;
      width: 300px;
      height: 300px;
    }

    @media (max-width: 400px) {
      h1 {
        font-size: 2rem;
      }
      p {
        font-size: 1rem;
      }
      canvas {
        width: 90vw;
        height: 90vw;
      }
    }
  </style>
</head>
<body>

  <header>
    <h1>404 - Tidak Ditemukan</h1>
    <p>Oops! Sepertinya kamu nyasar... 🐍</p>
  </header>

  <canvas id="snakeCanvas" width="300" height="300"></canvas>

  <footer>
    <p>&copy; 2025 - Halaman tidak ditemukan</p>
  </footer>

  <script>
    const canvas = document.getElementById('snakeCanvas');
    const ctx = canvas.getContext('2d');

    const gridSize = 15;
    const tileCount = canvas.width / gridSize;

    let snake = [{ x: 10, y: 10 }];
    let velocity = { x: 1, y: 0 };
    let food = { x: 15, y: 15 };
    let frame = 0;
    let gameRunning = true;

    document.addEventListener('keydown', (e) => {
      const key = e.key.toLowerCase();

      switch (key) {
        case 'arrowup':
        case 'w':
          if (velocity.y === 0) velocity = { x: 0, y: -1 };
          break;
        case 'arrowdown':
        case 's':
          if (velocity.y === 0) velocity = { x: 0, y: 1 };
          break;
        case 'arrowleft':
        case 'a':
          if (velocity.x === 0) velocity = { x: -1, y: 0 };
          break;
        case 'arrowright':
        case 'd':
          if (velocity.x === 0) velocity = { x: 1, y: 0 };
          break;
      }
    });

    function drawGame() {
      if (!gameRunning) return;

      if (++frame % 8 === 0) {
        update();
        draw();
      }

      requestAnimationFrame(drawGame);
    }

    function update() {
      const head = { x: snake[0].x + velocity.x, y: snake[0].y + velocity.y };

      // Loop ke sisi berlawanan jika keluar batas
      if (head.x < 0) head.x = tileCount - 1;
      if (head.x >= tileCount) head.x = 0;
      if (head.y < 0) head.y = tileCount - 1;
      if (head.y >= tileCount) head.y = 0;

      // Cek tabrakan dengan tubuh sendiri
      for (let i = 0; i < snake.length; i++) {
        if (snake[i].x === head.x && snake[i].y === head.y) {
          gameRunning = false;
          showGameOver();
          return;
        }
      }

      snake.unshift(head);

      if (head.x === food.x && head.y === food.y) {
        placeFood();
      } else {
        snake.pop();
      }
    }

    function draw() {
      ctx.fillStyle = '#000';
      ctx.fillRect(0, 0, canvas.width, canvas.height);

      ctx.fillStyle = '#0f0';
      snake.forEach(segment => {
        ctx.fillRect(segment.x * gridSize, segment.y * gridSize, gridSize - 2, gridSize - 2);
      });

      ctx.fillStyle = '#f00';
      ctx.fillRect(food.x * gridSize, food.y * gridSize, gridSize - 2, gridSize - 2);
    }

    function placeFood() {
      food = {
        x: Math.floor(Math.random() * tileCount),
        y: Math.floor(Math.random() * tileCount)
      };
    }

    function showGameOver() {
      ctx.fillStyle = '#f00';
      ctx.font = '18px Courier New';
      ctx.textAlign = 'center';
      ctx.fillText('GAME OVER', canvas.width / 2, canvas.height / 2);
    }

    draw();      // gambar awal
    drawGame();  // mulai game loop
  </script>
</body>
</html>

<?= $this->endSection() ?>