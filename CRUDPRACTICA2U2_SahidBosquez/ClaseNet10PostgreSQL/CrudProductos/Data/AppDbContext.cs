using CrudProductos.Models;
using Microsoft.EntityFrameworkCore;

namespace CrudProductos.Data
{
    public class AppDbContext : DbContext
    {
        public AppDbContext(DbContextOptions<AppDbContext> options) : base(options)
        {
        }

        public DbSet<Producto> Productos { get; set; }
        public DbSet<Customer> Customers { get; set; } // agreagmos la nueva tabla Customers a nuestro contexto 
    }
}
