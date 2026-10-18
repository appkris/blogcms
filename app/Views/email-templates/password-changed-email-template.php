<p>Dear <?= $mail_data['user']->name ?></p>
<br>
<p>
    Password akun Anda pada sistem Blog AppKris telah berhasil diubah.
    <br><br>
    <b>Username / Email:</b> <?= esc($mail_data['user']->username) ?> (<?= esc($mail_data['user']->email) ?>)
    <br>
    <b>Waktu Perubahan:</b> <?= date('d M Y, H:i') ?> WIB
</p>

<br>
<p>
    Jika Anda merasa tidak melakukan perubahan ini, segera hubungi web administrator untuk mengamankan akun Anda.
</p>

<br>
-------------------------------------------------------
<p>
    this email was automatically sent by CI4Blog system. do not reply it.
</p>