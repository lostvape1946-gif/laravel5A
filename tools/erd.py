import matplotlib
matplotlib.use("Agg")
import matplotlib.pyplot as plt
from matplotlib.patches import Rectangle, FancyBboxPatch

NAMA = "NAMA_ANDA"
NPM  = "NPM_ANDA"

tables = {
 "kategoris":   (0.3, 1.6, ["id (PK)", "nama", "created_at", "updated_at"]),
 "bukus":       (3.6, 0.9, ["id (PK)", "kategori_id (FK)", "judul", "penulis", "tahun", "created_at", "updated_at"]),
 "peminjamans": (6.9, 0.6, ["id (PK)", "anggota_id (FK)", "buku_id (FK)", "tanggal_pinjam", "tanggal_kembali", "created_at", "updated_at"]),
 "anggotas":    (10.2, 1.4, ["id (PK)", "nama", "npm", "email", "created_at", "updated_at"]),
}
W, RH = 2.6, 0.38
fig, ax = plt.subplots(figsize=(15, 6.5))
ax.set_xlim(0, 15); ax.set_ylim(0, 6.5); ax.axis("off")

pos = {}
for name,(x,ytop_off,fields) in tables.items():
    h = RH*(len(fields)+1)
    y = 6.0 - ytop_off - h + 0.8
    ax.add_patch(Rectangle((x,y), W, h, fc="white", ec="#1f3a5f", lw=1.8))
    ax.add_patch(Rectangle((x,y+h-RH), W, RH, fc="#1f3a5f", ec="#1f3a5f"))
    ax.text(x+W/2, y+h-RH/2, name, color="white", ha="center", va="center", fontsize=12, fontweight="bold")
    for i,f in enumerate(fields):
        fy = y+h-RH*(i+1.5)
        col = "#b00020" if "FK" in f else ("#1f3a5f" if "PK" in f else "black")
        ax.text(x+0.12, fy, f, va="center", fontsize=10.5, color=col, fontweight="bold" if ("PK" in f or "FK" in f) else "normal")
    pos[name] = dict(x=x,y=y,h=h,fields=fields)

def fy(t, field):
    p=pos[t]; i=p["fields"].index(field)
    return p["y"]+p["h"]-RH*(i+1.5)

def rel(a, fa, b, fb):
    # a (1) --- b (N); a is left of b
    x1=pos[a]["x"]+W; y1=fy(a,fa)
    x2=pos[b]["x"];   y2=fy(b,fb)
    xm=(x1+x2)/2
    ax.plot([x1,xm,xm,x2],[y1,y1,y2,y2], color="#444", lw=1.5)
    ax.text(x1+0.12,y1+0.1,"1",fontsize=11,fontweight="bold")
    ax.text(x2-0.25,y2+0.1,"N",fontsize=11,fontweight="bold")

rel("kategoris","id (PK)","bukus","kategori_id (FK)")
rel("bukus","id (PK)","peminjamans","buku_id (FK)")
# anggotas is right of peminjamans: N on peminjamans side
x1=pos["peminjamans"]["x"]+W; y1=fy("peminjamans","anggota_id (FK)")
x2=pos["anggotas"]["x"]; y2=fy("anggotas","id (PK)")
xm=(x1+x2)/2
ax.plot([x1,xm,xm,x2],[y1,y1,y2,y2], color="#444", lw=1.5)
ax.text(x1+0.1,y1+0.1,"N",fontsize=11,fontweight="bold")
ax.text(x2-0.25,y2+0.1,"1",fontsize=11,fontweight="bold")

ax.text(0.3,6.25,"Relasi Basisdata - Sistem Perpustakaan (Laravel)",fontsize=15,fontweight="bold",color="#1f3a5f")

# side panel
ax.add_patch(FancyBboxPatch((13.0,0.4),1.8,5.3,boxstyle="round,pad=0.05",fc="#eef3fa",ec="#1f3a5f",lw=1.5))
ax.text(13.9,3.05,f"Nama:\n{NAMA}\n\nNPM:\n{NPM}",rotation=90,ha="center",va="center",fontsize=14,fontweight="bold",color="#1f3a5f",linespacing=1.6)
fig.savefig("/home/claude/perpustakaan/gambar_relasi.png",dpi=150,bbox_inches="tight")
