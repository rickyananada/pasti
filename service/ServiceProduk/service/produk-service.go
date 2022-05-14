package service

import (
	"log"

	"github.com/mashingan/smapping"
	"github.com/rickyananda1/golang_gin_gorm_JWT/dto"
	"github.com/rickyananda1/golang_gin_gorm_JWT/entity"
	"github.com/rickyananda1/golang_gin_gorm_JWT/repository"
)

type ProdukService interface {
	Insert(b dto.ProdukCreateDTO) entity.Produk
	Update(b dto.ProdukUpdateDTO) entity.Produk
	Delete(b entity.Produk)
	All() []entity.Produk
	FIndById(produkID uint64) entity.Produk
}

type produkService struct {
	produkRepository repository.ProdukRepository
}

func NewProdukService(produkRepo repository.ProdukRepository) ProdukService {
	return &produkService{
		produkRepository: produkRepo,
	}
}

func (service *produkService) Insert(b dto.ProdukCreateDTO) entity.Produk {
	produk := entity.Produk{}
	err := smapping.FillStruct(&produk, smapping.MapFields(&b))
	if err != nil {
		log.Fatalf("Failed map %v:", err)
	}
	res := service.produkRepository.InsertProduk(produk)
	return res
}

func (service *produkService) Update(b dto.ProdukUpdateDTO) entity.Produk {
	produk := entity.Produk{}
	err := smapping.FillStruct(&produk, smapping.MapFields(&b))
	if err != nil {
		log.Fatalf("Failed map %v:", err)
	}
	res := service.produkRepository.UpdateProduk(produk)
	return res
}

func (service *produkService) Delete(b entity.Produk) {
	service.produkRepository.DeleteProduk(b)
}

func (service *produkService) All() []entity.Produk {
	return service.produkRepository.AllProduk()
}

func (service *produkService) FIndById(produkID uint64) entity.Produk {
	return service.produkRepository.FindProdukID(produkID)
}
